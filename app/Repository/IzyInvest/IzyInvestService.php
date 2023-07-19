<?php

namespace App\Repository\IzyInvest;

use App\Models\IzyInvest\IzyInvestPlan;
use App\Models\IzyInvest\IzyInvestTransaction;
use App\Models\IzyInvest\IzyInvestUserPlan;
use App\Models\User;
use App\Models\IzyPay as IzyPayModel;
use App\Repository\IzyPay\IzyPayService;
use App\Traits\SendResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\ConnectionInterface as DB;
use Illuminate\Support\Facades\Http;
use Log;
use Request;

class IzyInvestService implements IzyInvestInterface
{
    private DB $db;
    private $user;
    private IzyInvestPlan $plan;
    private IzyInvestUserPlan $userPlan;
    private IzyInvestTransaction $transaction;
    private IzyPayService $izyPay;

    use SendResponse;


    public function __construct(
        DB $db,
        IzyInvestPlan $plan,
        IzyInvestUserPlan $userPlan,
        IzyInvestTransaction $transaction,
        IzyPayService $izyPay
    )
    {
        $this->db = $db;
        $this->user = request()->user();
        $this->plan = $plan;
        $this->userPlan = $userPlan;
        $this->transaction = $transaction;
        $this->izyPay = $izyPay;
    }

    public function createPlan($data)
    {
        $this->plan->saveData($data);
    }


    /**
     * Get Current user's plans
     */
    public function getUserPlans()
    {
        $plans = $this->user->investPlans;

        return $this->sendSuccess($plans->toArray());
    }

    public function createUserPlan($data)
    {
        $plan = $this->plan->where('plan', $data['plan'])->first();
        $duration = Carbon::parse($data['payoutDate'])->diffInDays(Carbon::now()) + 1;

        if($duration < $plan->min_duration) {
            return $this->sendError(FORTY, 'Payout cannot be earlier than ' . Carbon::now()->addDays($plan->min_duration)->format('Y-m-d'));
        }

        $saveData = [];
        $saveData['izyinvest_plan_id'] = $plan->id;
        $saveData['user_id'] = $this->user->id;
        $saveData['account_name'] = $data['accountName'];
        $saveData['account_number'] = $data['accountNumber'];
        $saveData['bank_id'] = $data['bank'];
        $saveData['monthly_interest'] = $plan->monthly_interest;
        $saveData['daily_interest'] = $plan->monthly_interest/30;
        $saveData['duration'] = $duration;
        $saveData['end_date'] = $data['payoutDate'];

        return $this->userPlan->saveData($saveData);
    }

    public function deposit($data, $userPlan)
    {
        $activePlan = $this->userPlan->getActiveUserPlanWithId($userPlan->id);

        if(!$activePlan || empty($activePlan->plan)) {
            return $this->sendError(FORTY, 'Specified plan is not active');
        }

        if($activePlan->deposit_count == 0 && $data['amount'] < $activePlan->plan->min_init_deposit) {
            return $this->sendError(FORTY, 'First deposit should not be less than ' . $activePlan->plan->min_init_deposit . ' for this plan');
        }

        if($activePlan->deposit_count > 0 && $data['amount'] < $activePlan->plan->min_recur_deposit) {
            return $this->sendError(FORTY, 'Minimum allowe deposit is ' . $activePlan->plan->min_recur_deposit);
        }

        $user = request()->user();

        $wallet = $user->izyPay;

        if($data['amount'] > $wallet->balance) {
            return $this->sendError(FORTY, 'Insufficient funds in wallet');
        }

        $this->db->beginTransaction();

        try{
            $this->izyPay->withdraw(
                $user,
                $data['amount'],
                'transfer', ['details' => 'Izyinvest: Deposit for plan - ' . $activePlan->plan->plan]
            );

            $this->transaction->saveData([
                'user_id' => $user->id,
                'izyinvest_plan_id' => $activePlan->id,
                'type' => 'deposit',
                'amount' => $data['amount'],
                'opening_balance' => $activePlan->closing_balance,
                'closing_balance' => $activePlan->closing_balance + $data['amount']
            ]);

            $activePlan->contributory_balance += $data['amount'];
            $activePlan->available_balance += $data['amount'];
            $activePlan->deposit_count += 1;
            $activePlan->closing_balance += $data['amount'];
            $activePlan->save();

            $this->db->commit();
        } catch(Exception $e) {
            $this->db->rollBack();
            Log::error("Withdraw for Plan:- " . $e->getMessage());
            return $this->sendError(FORTY, "Unable to complete plan funding");
        }

        return $this->sendSuccess([]);
    }
}
