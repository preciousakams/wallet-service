<?php


namespace App\Repository\IzyBudget;


use App\Models\IzyBudgetPlan;
use App\Models\User;

interface IzyBudgetInterface
{
    public function getBalance();

    public function getIzyBudgetPlan();

    public function fundIzyBudget(IzyBudgetPlan $izyBudgetPlan);

    public function deposit(User $receiver, $amount, $type='deposit', $meta=[], $reference=null);
}


