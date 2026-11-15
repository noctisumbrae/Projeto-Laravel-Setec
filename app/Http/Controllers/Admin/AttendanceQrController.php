<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Services\QrCodeService;

class AttendanceQrController extends Controller
{
    // Exibe a tela com o QR Code
    public function show(Activity $activity, QrCodeService $qrCodeService)
    {
        $url = $qrCodeService->url($activity);
        $qrcode = $qrCodeService->svg($activity, 320);
        
        return view('admin.attendance.qrcode.show', compact('activity', 'qrcode', 'url'));
    }

    // Exibe a tela de impressão A4
    public function print(Activity $activity, QrCodeService $qrCodeService)
    {
        $url = $qrCodeService->url($activity);
        $qrcode = $qrCodeService->svg($activity, 420);
        
        return view('admin.attendance.qrcode.print-single', compact('activity', 'qrcode', 'url'));
    }
}