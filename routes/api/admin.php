<?php

use App\Http\Controllers\Administrator\{
    IzyInvestController,
    IzyPayController,
    ReferralController,
    PayoutController
};

Route::prefix('izypay')->group(function() {
    Route::get('', [IzyPayController::class, 'index'])->middleware('permission:izypay fetch');
    Route::get('details/{user:email}', [IzyPayController::class, 'details'])->middleware('permission:izypay details');
    Route::post('deposit', [IzyPayController::class, 'deposit'])->middleware('permission:izypay deposit');
});

Route::prefix('referral')->group(function() {
    Route::get('', [ReferralController::class, 'getReferrals']);
    Route::get('config', [ReferralController::class, 'getConfig']);
    Route::post('config/update', [ReferralController::class, 'updateConfig']);
    Route::post('payout/initiate', [PayoutController::class, 'initiatePayout']);
});
Route::prefix('payout')->group(function() {
    Route::post('request', [PayoutController::class, 'payoutRequest']);
    Route::post('process', [PayoutController::class, 'processPayout']);
});

Route::prefix('izyinvest')->group(function() {
    Route::get('plans', [IzyInvestController::class, 'plans']);
    Route::get('user/plans', [IzyInvestController::class, 'getUserPlans']);
    Route::post('create', [IzyInvestController::class, 'createPlan']);
    Route::put('plan/{id}', [IzyInvestController::class, 'updatePlan']);
});
