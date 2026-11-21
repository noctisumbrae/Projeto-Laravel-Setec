<?php

use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\HistoricoCertificadoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ValidacaoCertificadoController;
use App\Http\Middleware\SomenteAdministrador;
use Illuminate\Support\Facades\Route;

// Validacao publica de certificados - Livia
Route::get(
    '/validar-certificado',
    [ValidacaoCertificadoController::class, 'index']
)
    ->middleware('throttle:30,1')
    ->name('certificados.validar');

// Historico e download do participante autenticado - Mariana
Route::middleware('auth')->group(function () {

    Route::get(
        '/meus-certificados',
        [HistoricoCertificadoController::class, 'index']
    )->name('certificados.historico');

    Route::get(
        '/meus-certificados/{certificado}/pdf',
        [HistoricoCertificadoController::class, 'baixar']
    )->name('certificados.historico.pdf');

});

// Rotas demonstrativas disponiveis apenas no ambiente local
if (app()->environment('local')) {

    Route::get(
        '/certificados',
        [CertificadoController::class, 'index']
    )->name('certificados.index');

    Route::get(
        '/certificados/modelo',
        [CertificadoController::class, 'modelo']
    )->name('certificados.modelo');

    Route::get(
        '/certificados/pdf',
        [CertificadoController::class, 'baixarPdf']
    )->name('certificados.pdf');

}

// Relatorios administrativos - Livia
Route::middleware([SomenteAdministrador::class])
    ->group(function () {

        Route::get(
            '/relatorios',
            [RelatorioController::class, 'index']
        );

        Route::get(
            '/relatorios/inscricoes',
            [RelatorioController::class, 'inscricoes']
        );

        Route::get(
            '/relatorios/atividades',
            [RelatorioController::class, 'atividades']
        );

        Route::get(
            '/relatorios/presencas',
            [RelatorioController::class, 'presencas']
        );

        Route::get(
            '/relatorios/carga-horaria',
            [RelatorioController::class, 'cargaHoraria']
        );

        Route::get(
            '/relatorios/certificados',
            [RelatorioController::class, 'certificados']
        );

        Route::get(
            '/relatorios/ocupacao',
            [RelatorioController::class, 'ocupacao']
        );

    });
Route::post('/meus-certificados/{inscricao}/emitir', [\App\Http\Controllers\HistoricoCertificadoController::class, 'emitir'])->middleware('auth')->name('certificados.historico.emitir');
