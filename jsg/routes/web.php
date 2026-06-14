<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;

Route::redirect('/', '/admin/login');
// Route untuk men-generate transaksi Midtrans (sandbox)
Route::get('/payment', [MidtransController::class, 'createTransaction'])->name('payment.create');