<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIzyinvestUserPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('izyinvest_user_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('izyinvest_plan_id')->nullable(false);
            $table->uuid('user_id')->nullable(false);
            $table->string('account_name');
            $table->string('account_number');
            $table->float('monthly_interest');
            $table->float('daily_interest');
            $table->integer('duration');
            $table->integer('deposit_count')->default(0);
            $table->float('contributory_balance', 15)->default(0);
            $table->float('available_balance', 15)->default(0);
            $table->float('closing_balance', 15)->default(0);
            $table->enum('status', ['processing', 'completed'])->default('processing');
            $table->boolean('active')->default(1);
            $table->date('end_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentUpdate();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('izyinvest_plan_id')->references('id')->on('izyinvest_plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('izyinvest_user_plans');
    }
}
