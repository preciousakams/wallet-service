<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\IzyInvest\IzyInvestTransaction;
use App\Models\IzyInvest\IzyInvestUserPlan;
use App\Models\User;
use App\Repository\IzyInvest\IzyInvestService;
use Illuminate\Http\Request;

class IzyInvestController extends Controller
{
    private IzyInvestService $izyInvestSevice;
    private IzyInvestUserPlan $userPlan;
    private IzyInvestTransaction $planHistory;

    public function __construct( IzyInvestService $izyInvestSevice, IzyInvestUserPlan $userPlan, IzyInvestTransaction $planHistory, Request $request)
    {
        $this->izyInvestSevice = $izyInvestSevice;
        $this->userPlan = $userPlan;
        $this->planHistory = $planHistory;
    }

    public function userPlans()
    {
        return $this->izyInvestSevice->getUserPlans();
    }

    public function createPlan(Request $request)
    {
        $data = $this->validate($request, [
            'plan' => 'required|string|exists:izyinvest_plans,plan',
            'accountName' => 'required|string',
            'accountNumber' => 'required|string',
            'bank' => 'required|integer',
            'payoutDate' => 'required|string',
        ]);

        return $this->izyInvestSevice->createUserPlan($data);
    }

    public function planDetails(Request $request, $planId)
    {
        $plan = $this->userPlan->where([
            ['id', '=', $planId],
            ['user_id', '=', $request->user()->id]
            ])->first();
        return $this->sendSuccess($plan);
    }

    public function deposit(Request $request, $planId)
    {
        $data = $this->validate($request, [
            'amount' => 'required|numeric'
        ]);

        $plan = $this->userPlan->findOrFail($planId);

        return $this->izyInvestSevice->deposit($data, $plan);
    }

    public function getPlanHistory($planId, Request $request)
    {
        $history = $this->planHistory->with(['userPlan:id,izyinvest_plan_id', 'userPlan.plan:id,plan'])->where([
            ['user_id', '=', $request->user()->id],
            ['izyinvest_plan_id', '=', $planId]
        ])->get();

        return $this->sendSuccess($history->toArray());
    }
}
