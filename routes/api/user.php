<?php

use App\Http\Controllers\User\IzyPayController;
use App\Http\Controllers\User\IzyBudgetController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\IzyInvestController;
use App\Http\Controllers\User\ReferralController;

Route::group(['middleware' => ['auth:api_user', 'verified']], function() {

    //Validate Nuban
    Route::get('banks', [AccountController::class, 'getBanks']);
    Route::post('/validate/nuban', [AccountController::class, 'validateNuban']);

    //IzyPay
    Route::prefix('izypay')->as('izypay.')->group(function () {
        Route::get('balance', [IzyPayController::class, 'getBalance'])->name('balance');
        Route::post('transfer', [IzyPayController::class, 'transfer'])->name('transfer');
        Route::post('withdraw', [IzyPayController::class, 'withdraw'])->name('withdraw');
        Route::get('verify/transaction/{reference}', [IzyPayController::class, 'verifyTransaction'])->name('verify.transaction');
        Route::post('account/reserve', [IzyPayController::class, 'reserveAccount'])->name('reserve');
        Route::post('specta/pay', [IzyPayController::class, 'payWithSpecta'])->name('specta.pay');
    });

    //IzyBudget
    Route::prefix('izybudget')->as('izybudget.')->group(function () {
        Route::get('balance', [IzyBudgetController::class, 'getBalance'])->name('balance');
        Route::get('plans', [IzyBudgetController::class, 'getPlans'])->name('plans');
        Route::get('fund-account', [IzyBudgetController::class, 'fundAccount'])->name('fund.account');
    });

    //Referral
    Route::prefix('referral')->as('referral.')->group(function() {
        Route::get('balance', [ReferralController::class, 'balance']);

        Route::get('account/details', [ReferralController::class, 'getAccountDetails']);

        Route::post('account/update', [ReferralController::class, 'updateAccount']);

        Route::post('send-invitation', [ReferralController::class, 'sendInvitation']);

        Route::get('candidates', [ReferralController::class, 'candidates']);

        Route::get('', [ReferralController::class, 'referral']);
    });

    Route::prefix('invest')->as('invest.')->group(function() {
        Route::get('plans', [IzyInvestController::class, 'userPlans']);
        Route::get('{id}', [IzyInvestController::class, 'planDetails']);
        Route::get('{id}/history', [IzyInvestController::class, 'getPlanHistory']);
        Route::post('activate', [IzyInvestController::class, 'createPlan']);
        Route::post('{id}/deposit', [IzyInvestController::class, 'deposit']);
    });
});

