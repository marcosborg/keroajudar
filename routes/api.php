<?php

use App\Http\Controllers\Api\PagamentoController;
use Illuminate\Support\Facades\Route;

Route::post('/pagamentos/split/multibanco', [PagamentoController::class, 'criarMultibanco']);
Route::post('/pagamentos/split/mbway', [PagamentoController::class, 'criarMbway']);
Route::get('/eupago/callback', [PagamentoController::class, 'callback']);
