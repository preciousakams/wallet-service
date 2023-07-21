<?php


namespace App\Repository\Payout;


use App\Models\PayoutRequest;
use App\Models\ReferralPayoutHistory;
use App\Models\User;
use App\Services\Gaps\GapsService;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class PayoutService implements PayoutInterface
{

    public function initializePayout(User $referral, array $paymentData)
    {
        $response = GapsService::singleTransfer($paymentData);
        $responseCode = $response['code'];

        if ($responseCode == "1000" || $responseCode == "1100" ) {

            $referral->referralAccount->balance -= $paymentData['amount'];
            $referral->referralAccount->save();

            $payoutHistory = new ReferralPayoutHistory();
            $payoutHistory->referral()->associate($referral->id);
            $payoutHistory->amount = $paymentData['amount'];
            $payoutHistory->transaction_status = "success";
            $payoutHistory->transaction_reference = $response['reference'];
            $payoutHistory->transaction_gateway = "GAPS";
            $payoutHistory->transaction_details = $paymentData['remarks'];
            $payoutHistory->admin_id = auth()->user()->id;
            $payoutHistory->save();

            return ResponseBuilder::asSuccess(Response::HTTP_OK)
                ->withHttpCode(Response::HTTP_OK)
                ->withMessage("Payment successful")
                ->build();
        }

        return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
            ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->withMessage($response['message'])
            ->build();
    }

    public function logPayoutRequest($request)
    {
        $payout = new PayoutRequest();
        foreach ($request as $key => $value) {
            if(empty($value)) {
                continue;
            }
            $payout->{$key} = $value;
        }
        $payout->save();

        return $payout->toArray();
    }

    public function processPayout(array $data)
    {
        return GapsService::singleTransfer($data);
    }
}
