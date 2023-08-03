<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankToIzyinvestUserPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('izyinvest_user_plans', function (Blueprint $table) {
            $table->bigInteger('bank_id')->after('account_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('izyinvest_user_plans', function (Blueprint $table) {
            $table->dropColumn('bank_id');
        });
    }
}
