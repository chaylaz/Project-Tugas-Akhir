<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TagihanController;
use App\Http\Controllers\MidtransController;

Route::post('/login', [AuthController::class, 'login']);
//url endpoint : https://GANTI_DENGAN_DOMAIN_ASLI_KALIAN.com/api/checkout
Route::post('/checkout',[MidtransController::class, 'checkout']);
//url endpoint : https://GANTI_DENGAN_DOMAIN_ASLI_KALIAN.com/api/midtrans-webhook
// (jangan lupa daftarkan urk webhook ini di dashboard midtrans)
Route::post('/midtrans-webhook', [MidtransController::class, 'Webhook']);
Route::get('/tagihan/unpaid/{pelanggan_id}', [TagihanController::class, 'getUnpaid']);
Route::get('/tagihan/paid/{pelanggan_id}', [TagihanController::class, 'getPaid']);

?>

