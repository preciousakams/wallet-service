<?php

use App\Http\Controllers\User\IzyPayController;

Route::post('monnify/confirm', [IzyPayController::class, 'confirmTransfer'])->name('transfer.confirm');
Route::get('specta/confirm/{ref}', [IzyPayController::class, 'confirmSpectaPayment'])->name('stpecta.confirm');
