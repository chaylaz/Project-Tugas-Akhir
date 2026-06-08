use App\Http\Controllers\Api\TagihanController;

// Endpoint yang akan diakses oleh aplikasi Flutter Mas Anam
Route::get('/tagihan/unpaid/{pelanggan_id}', [TagihanController::class, 'getUnpaid']);
Route::get('/tagihan/paid/{pelanggan_id}', [TagihanController::class, 'getPaid']);