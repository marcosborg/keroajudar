<?php

use App\Http\Controllers\Api\PublicWebsiteController;
use App\Http\Controllers\Api\PagamentoController;
use Illuminate\Support\Facades\Route;

Route::post('/pagamentos/split/multibanco', [PagamentoController::class, 'criarMultibanco']);
Route::post('/pagamentos/split/mbway', [PagamentoController::class, 'criarMbway']);
Route::get('/eupago/callback', [PagamentoController::class, 'callback']);

Route::prefix('public')->group(function () {
    Route::get('/site', [PublicWebsiteController::class, 'site']);
    Route::get('/beneficiaries', [PublicWebsiteController::class, 'beneficiaries']);
    Route::get('/beneficiaries/{beneficiary}', [PublicWebsiteController::class, 'showBeneficiary']);
    Route::post('/beneficiaries/{beneficiary}/donations', [PublicWebsiteController::class, 'donate']);
});
