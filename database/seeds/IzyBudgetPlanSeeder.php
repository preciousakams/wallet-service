<?php

use Illuminate\Database\Seeder;
use App\Models\IzyBudgetPlan;

class IzyBudgetPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $planOne = new IzyBudgetPlan;
        $planOne->amount = 10000;
        $planOne->discount = 2;
        $planOne->save();

        $planTwo = new IzyBudgetPlan;
        $planTwo->amount = 25000;
        $planTwo->discount = 5;
        $planTwo->save();

        $planThree = new IzyBudgetPlan;
        $planThree->amount = 30000;
        $planThree->discount = 57;
        $planThree->save();
    }
}
