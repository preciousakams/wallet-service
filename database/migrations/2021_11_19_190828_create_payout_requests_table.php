<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->string('account_name');
            $table->string('bank_code');
            $table->float('amount');
            $table->string('reference');
            $table->string('description')->nullable();
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->string('remark')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payout_requests');
    }
}
