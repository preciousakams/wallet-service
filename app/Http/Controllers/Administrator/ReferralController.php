<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Repository\Referral\ReferralInterface;
use App\Http\Requests\Administrator\Referral\UpdateConfigRequest;
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

    public function getReferrals()
    {
        return $this->referralService->getReferralsAsAdmin();
    }

    public function getConfig()
    {
        return $this->referralService->getConfig();
    }

    public function updateConfig(UpdateConfigRequest $request)
    {
        return $this->referralService->updateConfig($request);
    }
}
