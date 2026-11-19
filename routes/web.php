<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AttendanceQrController;
use App\Http\Controllers\Admin\AttendanceAdminController; // <-- Nova importação
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rotas Públicas (Para quem escanear o QR Code)
Route::get('/presenca/{token}', [AttendanceController::class, 'showForm'])->name('attendance.form');
Route::post('/presenca/{token}', [AttendanceController::class, 'register'])->name('attendance.register');

// Rotas Administrativas (Painel e QR Code)
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Rotas do QR Code
    Route::get('/presencas/atividade/{activity}/qrcode', [AttendanceQrController::class, 'show'])->name('attendance.qrcode.show');
    Route::get('/presencas/atividade/{activity}/qrcode/print', [AttendanceQrController::class, 'print'])->name('attendance.qrcode.print');

    // Rotas do Painel de Presenças
    Route::get('/presencas', [AttendanceAdminController::class, 'index'])->name('attendance.index');
    Route::get('/presencas/atividade/{activity}', [AttendanceAdminController::class, 'byActivity'])->name('attendance.by-activity');
    
    // Rota de Presença Manual (Nova)
    Route::post('/presencas/atividade/{activity}/manual', [AttendanceAdminController::class, 'storeManual'])->name('attendance.store-manual');
});