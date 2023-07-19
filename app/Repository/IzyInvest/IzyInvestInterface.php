<?php


namespace App\Repository\IzyInvest;
use App\Models\User;
use Illuminate\Http\Request;

interface IzyInvestInterface
{
    public function getUserPlans();

    public function createPlan($data);

    public function createUserPlan($data);
}
