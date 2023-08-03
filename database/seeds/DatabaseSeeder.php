<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(IzyBudgetPlanSeeder::class);
         $this->call(BankTableSeeder::class);
    }
}
