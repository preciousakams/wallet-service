<?php

namespace App\Repository\IzyPay;

use App\Models\Bank;
use App\Models\ExternalRequest;
use App\Models\User;
use App\Models\IzyPay as IzyPayModel;
use App\Models\Transaction;
use App\services\MonnifyService;
use App\services\SpectaService;
use App\Traits\SendResponse;
use App\VirtualBankAccount;
use Illuminate\Database\ConnectionInterface as DB;
use App\Models\ReferralConfig;
use App\Models\ReferralAccount;
use App\Models\ReferralBonusHistory;
use Illuminate\Support\Facades\Http;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Http\Response;

class IzyPayService implements IzyPayInterface
{
    private DB $db;
    private $user;
    private IzyPayModel $izyPayModel;
    private Transaction $transaction;
    private ReferralConfig $referralConfig;
    private ReferralAccount $referralAccount;
    private ReferralBonusHistory $referralBonusHistory;

    use SendResponse;

    /**
     * IzyPayService constructor.
     * @param DB $db
     * @param IzyPayModel $izyPayModel
     * @param Transaction $transaction
     * @param ReferralConfig $referralConfig
     * @param ReferralAccount $referralAccount
     * @param ReferralBonusHistory $referralBonusHistory
     */
    public function __construct(
        DB $db,
        IzyPayModel $izyPayModel,
        Transaction $transaction,
        ReferralConfig $referralConfig,
        ReferralAccount $referralAccount,
        ReferralBonusHistory $referralBonusHistory
    )
    {
        $this->db = $db;
        $this->user = request()->user();
        $this->izyPayModel = $izyPayModel;
        $this->transaction = $transaction;
        $this->referralConfig = $referralConfig;
        $this->referralAccount = $referralAccount;
        $this->referralBonusHistory = $referralBonusHistory;
    }

    /**
     * Get current balance of the user
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function getBalance()
    {
        $account_details = null;
        $account = $this->user->virtualAccount;

        if(empty($account) && !empty($this->user->bvn)) {
            $this->reserveAccount(true);
            $account = $this->user->refresh()->virtualAccount;
        }


        if(!empty($account)) {
            $account_details = [
                'bank_name' => $account->bank->name,
                'account_number' => $account->account_number,
                'account_name' => $account->account_name
            ];
        }

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('izyPay Balance')
            ->withData([
                'virtual_account' => $account_details,
                'balance' => $this->user->izyPay->balance,
                'transactions' =>  $this->user->izyPay->transactions()->latest()->get()
            ])->build();
    }

    /**
     * Transfer funds from authenticated user to receiver
     *
     * @param User $receiver
     * @param $amount
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function transfer(User $receiver, $amount)
    {

        if (!$this->canWithdraw($amount)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('Insufficient balance')
                ->build();
        }

        if (!$receiver->hasVerifiedEmail()) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('You can\'t transfer funds to an unverified account')
                ->build();
        }

        $this->db->beginTransaction();

        $transactionRef = uniqid('lwch_',true);

        //Debit the giver
        $this->withdraw($this->user, $amount, 'Transfer',[
            'details' => 'Transferred the sum of ' . number_format($amount,2) . ' to ' . $receiver->getName(),
        ],$transactionRef);

        //Credit the receiver
        $this->deposit($receiver, $amount, 'Transfer', [
            'details' => 'Received the sum of ' . number_format($amount,2) . ' from ' . $this->user->getName(),
        ],$transactionRef);

        //Credit the referral of the receiver
       /* if ($receiver->hasReferral()) {
            $referralConfig = $this->referralConfig::first();

            $reward = ($referralConfig->commission_percentage / ONE_HUNDRED) * $amount;

            $referralAccount = $this->referralAccount::firstOrNew(['user_id' => $receiver->referral_id]);
            $referralAccount->balance += $reward;
            $referralAccount->save();

            $bonusHistory = new $this->referralBonusHistory();
            $bonusHistory->user_id = $receiver->referral_id;
            $bonusHistory->balance = $reward;
            $bonusHistory->save();

        }*/

        $this->db->commit();

        //Log the activity
        activity()
            ->performedOn($this->user->izyPay)
            ->causedBy($this->user)
            ->withProperties(['user_platform' => $this->user->getFullPlatformDetails()])
            ->log( "Transferred the sum of $amount to {$receiver->getName()}");

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Funds transferred successfully')
            ->build();

    }

    /**
     * @param $amount
     * @param $details
     * Withdraw sent from external request (endpoint)
     */
    public function externalWithdraw($amount, $details)
    {
        if (!$this->canWithdraw($amount)) {
            return $this->sendError(SOMETHING_WENT_WRONG, 'Insufficient balance', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //Withdraw from user izyPay
        $this->withdraw($this->user, $amount, 'Transfer',[
            'details' => $details,
        ]);

        return $this->sendSuccess(
            [
            'balance' => $this->user->izyPay->balance,
            'amount_withdrawn' => (float)$amount
            ],
            "$amount successfully withdrawn from izypay"
        );
    }

    /**
     * Validate Transaction from Paystack before funding user account
     *
     * @param String $reference
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function validatePaystackTransaction(string $reference)
    {
        $url = config('paystack.verify_url') . $reference;
        $secretKey = config('paystack.secret_key');
        $response = Http::withHeaders(['Authorization' => "Bearer $secretKey"])->get($url);

        $paymentData = json_decode($response->body(), true);

        if (!$paymentData['status']) {
            return $this->sendError(SOMETHING_WENT_WRONG, $paymentData['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($paymentData['data']['status'] == 'success' && $response->successful()) {

            if (!$this->transaction::where('reference', $paymentData['data']['reference'])->exists()) {
                $amountPaid = $paymentData['data']['amount'] / ONE_HUNDRED;
                $user =  $this->user::where('email', $paymentData['data']['customer']['email'])->firstOrFail();

                $this->deposit($user, $amountPaid, 'deposit', [
                    'details' => 'Deposited the sum of' . number_format($amountPaid,2)
                ], $paymentData['data']['reference']);

                /*if ($user->hasReferral()) {
                    $referralConfig = $this->referralConfig::first();

                    $reward = ($referralConfig->commission_percentage / ONE_HUNDRED) * $amountPaid;

                    $referralAccount = $this->referralAccount::firstOrNew(['user_id' => $user->referral_id]);
                    $referralAccount->balance += $reward;
                    $referralAccount->save();

                    $bonusHistory = new $this->referralBonusHistory();
                    $bonusHistory->user_id = $user->referral_id;
                    $bonusHistory->balance = $reward;
                    $bonusHistory->save();
                }*/
            }
            return $this->sendSuccess([], 'Funds deposited successfully');
        }

        return $this->sendError(
            SOMETHING_WENT_WRONG,
            'Error occurred, transaction could not verified, try again later or contact Administrator for more info',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }


    /**
     * Deposit funds to user izypay
     *
     * @param User $receiver
     * @param $amount
     * @param string $type
     * @param array|null $meta
     * @param string|null $reference
     * @param IzyPayModel|null $izyPay
     *
     * @return void
     */
    public function deposit(User $receiver, $amount, $type = 'deposit', $meta = [], $reference = null, IzyPayModel $izyPay = null): void
    {
        $this->db->beginTransaction();

        $izyPay = !empty($izyPay) ? $izyPay : $receiver->izyPay;
        $izyPay->balance += $amount;
        $izyPay->save();

        $transaction = new $this->transaction;
        $transaction->transactionable()->associate($izyPay);
        $transaction->amount = $amount;
        $transaction->reference = $reference ?? uniqid('lwch_',true);
        $transaction->type = $type;
        $transaction->meta = $meta;
        $transaction->save();

        $this->db->commit();

        //Log the activity
        activity()
            ->performedOn($izyPay)
            ->causedBy($this->user ?? $receiver)
            ->withProperties(['user_platform' => !empty($this->user) ? $this->user->getFullPlatformDetails() : $receiver->getFullPlatformDetails()])
            ->log( "deposited the sum of $amount");
    }

    /**
     * Check if funds can be withdrawn from user account
     *
     * @param $amount
     *
     * @return bool
     */
    public function canWithdraw($amount):bool
    {
        return $this->user->izyPay->balance >= $amount;
    }

    /**
     * Withdraw funds from debtor's account
     *
     * @param User $debtor
     * @param $amount
     * @param string $type
     * @param array|null $meta
     * @param string|null $reference
     * @param bool|true $checkAccept
     *
     * @return void
     */
    public function withdraw(User $debtor, $amount, $type, $meta = [], $reference = null, $checkAccept = true)
    {
        $this->db->beginTransaction();

        $accepted = $checkAccept ? $this->canWithdraw($amount) : true;

        if ($accepted) {
            $debtor->izyPay->balance -= $amount;
            $debtor->izyPay->save();

            $transaction = new $this->transaction;
            $transaction->transactionable()->associate($debtor->izyPay);
            $transaction->amount = $amount;
            $transaction->reference = $reference ?? uniqid('lwch_',true);
            $transaction->type = $type;
            $transaction->meta = $meta;
            $transaction->save();

            $this->db->commit();

            //Log the activity
            activity()
                ->performedOn($debtor->izyPay)
                ->causedBy($this->user)
                ->withProperties(['user_platform' => $this->user->getFullPlatformDetails()])
                ->log( "Withdraw the sum of $amount");

        } elseif(!$debtor->izyPay->exist) {
            $debtor->izyPay->save();
        }
    }

    /**
     * Force Withdraw funds from debtor's account
     *
     * @param $amount
     * @param string $type
     * @param array|null $meta
     *
     * @return void
     */
    public function forceWithdraw($amount, $type, $meta = []): void
    {
        $this->withdraw($this->user, $amount, $type = 'withdraw', $meta, null, false);
    }

    /**
     * @param bool $return
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function reserveAccount($return = false)
    {
        try {
            $monnify = new MonnifyService();
            $response = $monnify->reserveAccount($this->user);

            if($response->getStatusCode() != ZERO) {
                return $this->sendError($response->getStatusCode(), $response->getMessage());
            }

            foreach ($response->getData()['accounts'] as $account) {
                $saveData = [
                    'user_id' => $this->user->id,
                    'account_name' => $response->getData()['accountName'],
                    'account_number' => $account['accountNumber'],
                    'bank_id' => Bank::where('code', $account['bankCode'])->first()->id,
                    'status' => 'enabled',
                ];

                VirtualBankAccount::add($saveData);
            }

            if($return){
                return $saveData['bank']['name'] =  $account['bankName'];
            }

            return $this->sendSuccess($response->getData(), 'account reserved');
        } catch (\Exception $e) {
            return $this->sendError('400', $e->getMessage());
        }

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAccountBVN(): \Symfony\Component\HttpFoundation\Response
    {
        $monnify = new MonnifyService();
        $response = $monnify->updateReservedAccountWithBVN($this->user);

        if($response->getStatusCode() != ZERO) {
            return$this->sendError($response->getStatusCode(), $response->getMessage());
        }

        return $this->sendSuccess($response->getData(), 'account updated');
    }

    public function confirmTransfer($data)
    {
        $userIzypay = $this->izyPayModel::where('id', $data->product['reference'])->first();
        $monnify = new MonnifyService();

        $log =ExternalRequest::log($monnify::SERVICE_NAME, 'hook', null, null, $userIzypay->user->id, json_encode($data));

        if(!$monnify->validateHash($data)) {
            $log->response = $monnify::UNABLE_TO_CONFIRM_TRANSACTION;
            $log->update();

            return $this->sendError($monnify::ERROR_STATUS_CODE, $monnify::UNABLE_TO_CONFIRM_TRANSACTION);
        }

        $transaction = $this->transaction::where('reference', $data->paymentReference)->first();

        if($transaction) {
            return $this->sendSuccess([]);
        }

        $meta = [
            'details' => sprintf(MonnifyService::TRANSFER_NARRATION, $data->accountDetails['accountName'], $data->accountDetails['accountNumber'])
        ];

        $this->deposit($userIzypay->user, $data->totalPayable, 'deposit', $meta, $data->paymentReference);

        return $this->sendSuccess([]);

    }

    public function payWithSpecta($data)
    {
        $specta = new SpectaService();
        $response = $specta->createPaymentUrl(uniqid('lwch_',true), $data->amount, $this->user->id);

        if($response->getStatusCode() != Response::HTTP_OK) {
            return $this->sendError($response->getStatusCode(), $response->getMessage());
        }

        return $this->sendSuccess($response->getData());
    }

    public function confirmSpectaPayment($data, $reference)
    {
        $specta = new SpectaService();
        $response = $specta->verifyPayment(urldecode($data->ref), $reference);

        if($response->getStatusCode() != Response::HTTP_OK) {
            return $this->sendError($response->getStatusCode(), $response->getMessage());
        }

        $data = $response->getData();

        $transaction = $this->transaction::where('reference', $data['paymentReference'])->first();

        if($transaction) {
            return $this->sendSuccess([]);
        }

        $userIzypay = $this->izyPayModel::where('user_id', $data['user_id'])->first();

        if(!$userIzypay) {
            return $this->sendError(400, 'No wallet found');
        }

        $meta = [
            'details' => sprintf(SpectaService::NARRATION, $data['reference'])
        ];

        $this->deposit($userIzypay->user, $data['amount'], 'deposit', $meta, $data['paymentReference']);

        return redirect(config('app.frontend_url'));
    }
}
