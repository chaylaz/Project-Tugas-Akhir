<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    // Fungsi untuk mengambil tagihan yang belum dibayar (status_pembayaran = 'belum')
    public function getUnpaid($pelanggan_id)
    {
        $tagihan = Tagihan::where('pelanggan_id', $pelanggan_id)
                          ->where('status_pembayaran', 'belum')
                          ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tagihan
        ]);
    }

    // Fungsi untuk mengambil riwayat tagihan yang sudah lunas
    public function getPaid($pelanggan_id)
    {
        $tagihan = Tagihan::where('pelanggan_id', $pelanggan_id)
                          ->where('status_pembayaran', 'lunas')
                          ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tagihan
        ]);
    }
}