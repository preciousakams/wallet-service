<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\Referral\InitiatePayoutRequest;
use App\Models\User;
use App\Repository\Payout\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class PayoutController extends Controller
{
    private User $user;

    private PayoutService $payoutService;

    /**
     * PayoutController constructor.
     * @param User $user
     * @param PayoutService $payoutService
     */
    public function __construct(User $user, PayoutService $payoutService)
    {
        $this->user = $user;
        $this->payoutService = $payoutService;
    }

    public function initiatePayout(InitiatePayoutRequest $request)
    {
        $referral = $this->user->findOrFail($request->referral_id);

        if (!$referral->referralAccount()->exists()) {

            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('Referral doesn\'t have bank details')
                ->build();
        }


        if (!$referral->hasReferralBankAccountInfo()) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('Referral needs to update bank account info')
                ->build();
        }

        if ($request->amount > $referral->referralAccount->balance) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('You can\'t pay the referral more than the earned amount')
                ->build();
        }

        $paymentData = $request->only('amount',
            'remarks',
            'gaps_username',
            'gaps_accesscode',
            'gaps_password');

        $paymentData['vendoracctnumber'] = $referral->referralAccount->account_number;
        $paymentData['vendorname'] = $referral->referralAccount->account_name;
        $paymentData['vendorcode'] = $referral->id;
        $paymentData['vendorbankcode'] = $referral->referralAccount->bank->code;

        return $this->payoutService->initializePayout($referral, $paymentData);
    }

    public function payoutRequest(Request $request)
    {
        $data = $this->validate($request, [
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'bank_code' => 'required|string',
            'amount' => 'required|numeric',
            'reference' => 'required|string|unique:payout_requests',
            'description' => 'string'
        ]);

        return $this->sendSuccess($this->payoutService->logPayoutRequest($data));
    }

}
