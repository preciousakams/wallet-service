<?php


namespace App\Repository\IzyBudget;


use App\Models\IzyBudget as IzyBudgetModel;
use App\Models\IzyBudgetPlan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\ConnectionInterface as DB;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Repository\IzyPay\IzyPayService;

class IzyBudgetService implements IzyBudgetInterface
{
    private DB $db;
    private $user;
    private IzyBudgetModel $izyBudgetModel;
    private IzyBudgetPlan $izyBudgetPlan;
    private Transaction $transaction;
    private IzyPayService $izyPayService;

    public function __construct(
        DB $db,
        IzyBudgetModel $izyBudgetModel,
        Transaction $transaction,
        IzyBudgetPlan $izyBudgetPlan,
        IzyPayService $izyPayService
    )

    {
        $this->db = $db;
        $this->user = request()->user();
        $this->izyBudgetModel = $izyBudgetModel;
        $this->izyBudgetPlan = $izyBudgetPlan;
        $this->transaction = $transaction;
        $this->izyPayService = $izyPayService;
    }

    public function getBalance()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('izyBudget Balance')
            ->withData([
                'balance' => $this->user->izyBudget->balance,
                'transactions' => $this->user->izyBudget->transactions
            ])
            ->build();
    }

    public function getIzyBudgetPlan()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('izyBudget Balance')
            ->withData([
                'plans' => $this->izyBudgetPlan::all(),
            ])
            ->build();
    }

    public function fundIzyBudget(IzyBudgetPlan $izyBudgetPlan)
    {
        if ($this->izyPayService->canWithdraw($izyBudgetPlan->amount)) {

            $this->db->beginTransaction();

            $transactionRef = uniqid('lwch_',true);

            //Withdraw from IzyPay
            $amountToWithdraw = $izyBudgetPlan->amount - (($izyBudgetPlan->discount / ONE_HUNDRED) * $izyBudgetPlan->amount);
            $this->izyPayService->withdraw($this->user, $amountToWithdraw, 'Transferred to izyBudget', [
                'details' => 'Transferred the sum of ' . number_format($izyBudgetPlan->amount,2) . ' to izyBudget with ' . $izyBudgetPlan . '% discount',
            ], $transactionRef);

            //Deposit IzyBudget
            $this->deposit($this->user, $izyBudgetPlan->amount, 'Deposit', [
                'details' => 'funded izyBudget with the sum of ' . number_format($izyBudgetPlan->amount,2)
            ]);

            $this->db->commit();

            //Log the activity
            activity()
                ->performedOn($this->user->izyBudget)
                ->causedBy($this->user)
                ->withProperties(['user_platform' => $this->user->getFullPlatformDetails()])
                ->log( "Transferred the sum of $izyBudgetPlan->amount to izyBudget");

            return ResponseBuilder::asSuccess(Response::HTTP_OK)
                ->withHttpCode(Response::HTTP_OK)
                ->withMessage('Funds transferred successfully')
                ->build();
        }

        return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
            ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->withMessage('Insufficient balance')
            ->build();
    }


    public function deposit(User $receiver, $amount, $type = 'deposit', $meta = [], $reference = null)
    {
        $this->db->beginTransaction();

        $izyBudget = $receiver->izyBudget;
        $izyBudget->balance = $amount;
        $izyBudget->save();

        $transaction = new $this->transaction;
        $transaction->transactionable()->associate($izyBudget);
        $transaction->amount = $amount;
        $transaction->reference = $reference ?? uniqid('lwch_',true);
        $transaction->type = $type;
        $transaction->meta = $meta;
        $transaction->save();

        $this->db->commit();

        //Log the activity
        activity()
            ->performedOn($izyBudget)
            ->causedBy($this->user)
            ->withProperties(['user_platform' => $this->user->getFullPlatformDetails()])
            ->log( "deposited the sum of $amount");
    }
}
