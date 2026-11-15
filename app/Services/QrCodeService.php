<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    // Garante que a atividade tenha um token único
    public function ensureToken(Activity $activity): string
    {
        if ($activity->hasToken()) {
            return $activity->qr_token;
        }

        $token = Str::random(64);
        $activity->update(['qr_token' => $token]);

        return $token;
    }

    // Regenera o token (invalida os QR Codes impressos anteriormente)
    public function regenerateToken(Activity $activity): string
    {
        $token = Str::random(64);
        $activity->update(['qr_token' => $token]);

        return $token;
    }

    // Gera a URL que vai dentro do QR Code
    public function url(Activity $activity): string
    {
        return route('attendance.form', $this->ensureToken($activity));
    }

    // Gera a imagem do QR Code em SVG (ideal para impressão)
    public function svg(Activity $activity, int $size = 320): string
    {
        return QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($this->url($activity));
    }

    // Gera a imagem em PNG em base64 (para relatórios em PDF)
    public function pngBase64(Activity $activity, int $size = 300): string
    {
        return base64_encode(
            QrCode::format('png')
                ->size($size)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($this->url($activity))
        );
    }
}