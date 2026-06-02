<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(array $orderData): string
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $orderData['order_id'],
                'gross_amount' => $orderData['total_bayar'],
            ],
            'customer_details' => [
                'first_name' => 'Pelanggan Meja ' . $orderData['no_meja'],
            ],
            'item_details' => $orderData['items'],
        ];

        return Snap::getSnapToken($params);
    }
}
