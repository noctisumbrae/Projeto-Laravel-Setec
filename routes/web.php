<?php

use App\Http\Controllers\EdicaoController;
use App\Http\Controllers\InscricaoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceQrController;
use App\Http\Controllers\AttendanceAdminController;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::resource('edicoes', EdicaoController::class)
    ->parameters(['edicoes' => 'edicao'])
    ->except(['destroy']);

Route::patch('edicoes/{edicao}/ativar', [EdicaoController::class, 'ativar'])->name('edicoes.ativar');
Route::patch('edicoes/{edicao}/encerrar', [EdicaoController::class, 'encerrar'])->name('edicoes.encerrar');
Route::resource('locais', LocalController::class)
    ->parameters(['locais' => 'local']);
Route::resource('atividades', AtividadeController::class)
    ->parameters(['atividades' => 'atividade']);
Route::resource('speakers', SpeakerController::class);

Route::resource('inscricoes', InscricaoController::class)
    ->parameters(['inscricoes' => 'inscricao']);
Route::get('/presencas', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/presencas', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/presencas/qrcode', [AttendanceQrController::class, 'show'])->name('attendance.qr');
Route::get('/admin/presencas', [AttendanceAdminController::class, 'index'])->name('admin.attendance.index');
Route::get('/admin/presencas/export', [AttendanceAdminController::class, 'export'])->name('admin.attendance.export');
Route::post('/admin/presencas/{attendance}/reverter', [AttendanceAdminController::class, 'reverter'])->name('admin.attendance.reverter');
Route::post('/admin/presencas/{attendance}/desfazer-reversao', [AttendanceAdminController::class, 'desfazerReversao'])->name('admin.attendance.desfazer-reversao');
Route::get('/admin/presencas/{attendance}', [AttendanceAdminController::class, 'show'])->name('admin.attendance.show');
