<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleMidtrans(Request $request)
    {
        $serverKey     = config('midtrans.server_key');
        $payload       = $request->all();
        $orderId       = $payload['order_id'] ?? '';
        $statusCode    = $payload['status_code'] ?? '';
        $grossAmount   = $payload['gross_amount'] ?? '';
        $signatureKey  = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Verifikasi signature
        if ($signatureKey !== ($payload['signature_key'] ?? '')) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari pesanan via midtrans_order_id — jika tidak ditemukan (misalnya test notification), tetap return 200
        $pesanan = Pesanan::where('midtrans_order_id', $orderId)->first();
        if (!$pesanan) {
            return response()->json(['message' => 'Order not found, notification acknowledged'], 200);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? null;

        if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
            $pesanan->status_pembayaran = 'lunas';
        } elseif ($transactionStatus === 'settlement') {
            $pesanan->status_pembayaran = 'lunas';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pesanan->status_pembayaran = 'gagal';
        } elseif ($transactionStatus === 'pending') {
            $pesanan->status_pembayaran = 'menunggu';
        }

        $pesanan->save();

        return response()->json(['message' => 'OK']);
    }
}
