<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\IzyPay;
use App\Http\Requests\Administrator\IzyPay\DepositUserRequest;
use App\Models\IzyInvest\IzyInvestPlan;
use App\Models\IzyInvest\IzyInvestUserPlan;
use App\Repository\IzyPay\IzyPayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\ResponseTrait;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class IzyInvestController extends Controller
{
    use ResponseTrait;

    private IzyInvestPlan $plan;

    public function __construct(IzyInvestPlan $plan)
    {
        $this->plan = $plan;
    }

    public function plans()
    {
        $plans = $this->plan->where('active', 1)->get();

        return $this->sendSuccess($plans->toArray());
    }

    public function createPlan(Request $request)
    {
        $data = $this->validate($request, [
            'plan' => 'required|string',
            'monthly_interest' => 'numeric',
            'min_duration' => 'integer',
            'min_init_deposit' => 'numeric',
            'min_recur_deposit' => '|numeric'
        ]);

        $result = $this->plan->saveData($data);

        return $this->sendSuccess($result);
    }

    public function updatePlan(Request $request, $id)
    {
        $plan = IzyInvestPlan::findOrFail($id);

        $data = $this->validate($request, [
            'plan' => 'string|unique:izyinvest_plans',
            'monthly_interest' => 'numeric',
            'min_duration' => 'integer',
            'min_init_deposit' => 'numeric',
            'min_recur_deposit' => 'numeric'
        ]);

        $plan->update($data);

        return $this->sendSuccess($plan);
    }

    public function getUserPlans(Request $request)
    {
        $userPlans = IzyInvestUserPlan::query();
        $filter = $request->only('status','user_id', 'account_name','active');

        if(!empty($filter)) {
            $condition = [];

            foreach($filter as $key => $value) {
                $condition[] = in_array($key, ['status', 'user_id', 'active']) ? [$key, $value] : [$key, 'LIKE', "%$value%"];
            }
        }

        if(!empty($condition)) {
            $userPlans = $userPlans->where($condition);
        }

        if($request->get('plan')) {
            $userPlans = $userPlans->whereHas('plan', fn($q) => $q->where('plan', $request->get('plan')));
        }

        if($request->get('expires')) {
            $dates = explode(',', $request->get('expires'));
            $from = Carbon::createFromFormat('Y-m-d', $dates[0]);

            if(!empty($dates[1])) {
                $to = Carbon::createFromFormat('Y-m-d', $dates[1]);
            }

            if(!empty($dates[1])) {
                $userPlans = $userPlans->whereBetween('end_date', [$from, $to]);
            } else {
                $userPlans = $userPlans->whereDate('end_date', Carbon::createFromFormat('Y-m-d', $dates[0]));
            }
        }

        return $this->sendSuccess($userPlans->get());
    }
}
