<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repository\Referral\ReferralInterface;
use App\Http\Requests\User\Referral\SendInvitationRequest;
use App\Http\Requests\User\Referral\UpdateReferralAccountRequest;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    private ReferralInterface $referralService;

    /**
     * ReferralController constructor.
     * @param ReferralInterface $referralService
     */
    public function __construct(ReferralInterface $referralService)
    {
        $this->referralService = $referralService;
    }

    public function balance()
    {
        return $this->referralService->getBalance();
    }

    public function getAccountDetails()
    {
        return $this->referralService->getAccountDetails();
    }

    public function updateAccount(UpdateReferralAccountRequest $request)
    {
        return $this->referralService->updateAccountDetails($request);
    }

    public function sendInvitation(SendInvitationRequest $request)
    {
        return $this->referralService->sendInvitation($request->email, $request->callbackUrl);
    }

    public function candidates()
    {
        return $this->referralService->getReferredCandidates();
    }

    public function referral()
    {
        return $this->referralService->getReferral();
    }
}
