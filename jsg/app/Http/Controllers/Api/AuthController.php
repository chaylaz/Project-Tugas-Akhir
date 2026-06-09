<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Pastikan memanggil model user$user
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi bahwa NIK dan Password tidak boleh kosong
        $request->validate([
            'nik' => 'required',
            'password' => 'required'
        ]);

        // 2. Cari user$user berdasarkan NIK yang dikirim dari Flutter
        $user = User::where('nik', $request->nik)->first();

        // 3. Jika NIK tidak ada di database
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIK tidak terdaftar.'
            ], 404);
        }

        // 4. Cek Password. 
        // Menggunakan Hash::check jika teman Anda mengenkripsi passwordnya.
        // Atau == langsung jika teman Anda iseng mengetik '123' secara mentah (plain text) di database.
        if (Hash::check($request->password, $user->password) || $user->password === $request->password) {
            
            // Berhasil Login! Kembalikan data user$user ke Flutter
            return response()->json([
                'status' => 'success',
                'message' => 'Login Berhasil',
                'data' => $user
            ], 200);
        }

        // 5. Jika password salah
        return response()->json([
            'status' => 'error',
            'message' => 'Kata sandi salah.'
        ], 401);
    }
}