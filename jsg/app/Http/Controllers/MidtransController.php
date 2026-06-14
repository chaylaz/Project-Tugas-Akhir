<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'harga_total' => 'required|numeric'
        ]);

        $order_id = 'TRX-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $request->harga_total,
            ],
            'customer_details' => [
                'first_name' => 'Pelanggan WiFi',
                // ---> EMAIL BISA DIUBAH ATAU DIBIARKAN SEBAGAI DEFAULT
                'email' => 'admin@GANTI_DENGAN_DOMAIN_ASLI_KALIAN.com',
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'status' => 'success',
                'token' => $snapToken,
                'order_id' => $order_id
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        try {
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $order_id = $notif->order_id;

            if ($transaction == 'settlement') {
                // TODO: Update status tagihan di database jadi 'Lunas'
            } else if ($transaction == 'expire' || $transaction == 'cancel' || $transaction == 'deny') {
                // TODO: Update status tagihan di database jadi 'Batal'
            }

            return response()->json(['message' => 'Notifikasi berhasil diterima server'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}