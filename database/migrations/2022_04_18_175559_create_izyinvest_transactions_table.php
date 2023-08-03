<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIzyinvestTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('izyinvest_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable(false);
            $table->uuid('izyinvest_plan_id')->nullable(false);
            $table->enum('type', ['deposit', 'withdrawal']);
            $table->float('amount');
            $table->float('opening_balance');
            $table->float('closing_balance');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('izyinvest_plan_id')->references('id')->on('izyinvest_user_plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('izyinvest_transactions');
    }
}
