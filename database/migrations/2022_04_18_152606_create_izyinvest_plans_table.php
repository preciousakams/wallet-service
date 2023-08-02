<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIzyinvestPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('izyinvest_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('plan');
            $table->float('monthly_interest');
            $table->float('min_duration');
            $table->float('min_init_deposit');
            $table->float('min_recur_deposit');
            $table->boolean('active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('izyinvest_plans');
    }
}
