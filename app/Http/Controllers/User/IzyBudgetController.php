<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IzyBudget\IzyBudgetFundingRequest;
use App\Repository\IzyBudget\IzyBudgetInterface;
use Illuminate\Http\Request;
use App\Models\IzyBudgetPlan;

class IzyBudgetController extends Controller
{
    private IzyBudgetInterface $izyBudgetService;
    private IzyBudgetPlan $izyBudgetPlan;

    public function __construct(IzyBudgetInterface $izyBudgetService, IzyBudgetPlan $izyBudgetPlan)
    {
        $this->izyBudgetService = $izyBudgetService;
        $this->izyBudgetPlan = $izyBudgetPlan;
    }

    public function getBalance()
    {
        return $this->izyBudgetService->getBalance();
    }

    public function getPlans()
    {
        return $this->izyBudgetService->getIzyBudgetPlan();
    }

    public function fundAccount(IzyBudgetFundingRequest $request)
    {
        $plan = $this->izyBudgetPlan::findOrFail($request->plan_id);
        return $this->izyBudgetService->fundIzyBudget($plan);
    }
}
