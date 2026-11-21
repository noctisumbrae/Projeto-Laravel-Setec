<?php

use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ValidacaoCertificadoController;
use App\Http\Middleware\SomenteAdministrador;
use Illuminate\Support\Facades\Route;

// ROTA PÚBLICA
Route::get(
    '/validar-certificado',
    [ValidacaoCertificadoController::class, 'index']
)
->middleware('throttle:30,1')
->name('certificados.validar');

// ROTAS RESTRITAS
Route::middleware([SomenteAdministrador::class])
    ->group(function () {

        Route::get('/relatorios',
            [RelatorioController::class, 'index']);

        Route::get('/relatorios/inscricoes',
            [RelatorioController::class, 'inscricoes']);

        Route::get('/relatorios/atividades',
            [RelatorioController::class, 'atividades']);

        Route::get('/relatorios/presencas',
            [RelatorioController::class, 'presencas']);

        Route::get('/relatorios/carga-horaria',
            [RelatorioController::class, 'cargaHoraria']);

        Route::get('/relatorios/certificados',
            [RelatorioController::class, 'certificados']);

        Route::get('/relatorios/ocupacao',
            [RelatorioController::class, 'ocupacao']);
    });
