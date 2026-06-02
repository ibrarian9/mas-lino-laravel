<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generate()
    {
        $url = url('/');
        $qrCode = QrCode::format('svg')->size(300)->generate($url);
        return view('admin.qrcode', compact('qrCode', 'url'));
    }
}
