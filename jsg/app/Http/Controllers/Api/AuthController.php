<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi bahwa NIK tidak boleh kosong
        $request->validate([
            'nik' => 'required',
        ]);

        // 2. Cari user berdasarkan NIK yang dikirim dari Flutter
        $user = User::with('pelanggan.paket')->where('nik', $request->nik)->first();

        // 3. Jika NIK tidak ada di database
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIK tidak terdaftar.'
            ], 404);
        }

        // 4. Login berhasil (menggunakan default password, tidak perlu input password)
        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil',
            'data' => $user
        ], 200);
    }
}