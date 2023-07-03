<?php

namespace App\Console\Commands;

use App\Models\PayoutRequest;
use App\Repository\Payout\PayoutService;
use Illuminate\Console\Command;

class ProcessPayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Payout';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PayoutService $payoutService)
    {
        $requests = PayoutRequest::where('status', 'pending')->get();
        if(empty($requests)) {
            return 1;
        }

        foreach ($requests as $item) {
            $item->status = 'processing';
            $item->save();

            $paymentData = [
                'amount' => $item->amount,
                'remarks' => $item->description,
                'vendoracctnumber' => $item->account_number,
                'vendorname' => $item->account_name,
                'vendorcode' => $item->reference,
                'vendorbankcode' => $item->bank_code
            ];

            $response = $payoutService->processPayout($paymentData);
            $item->remark = json_encode($response);

            $item->status = $response['code'] == 1000 ? 'success' : 'failed';

            $item->save();
        }

        return 1;
    }
}
