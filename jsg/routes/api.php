<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TagihanController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/tagihan/unpaid/{pelanggan_id}', [TagihanController::class, 'getUnpaid']);
Route::get('/tagihan/paid/{pelanggan_id}', [TagihanController::class, 'getPaid']);
?>