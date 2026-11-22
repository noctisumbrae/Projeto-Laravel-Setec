<?php

use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\EdicaoController;
use App\Http\Controllers\HistoricoCertificadoController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\ValidacaoCertificadoController;
use App\Http\Middleware\SomenteAdministrador;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('edicoes', EdicaoController::class)
    ->parameters(['edicoes' => 'edicao'])
    ->except(['destroy']);

Route::patch('edicoes/{edicao}/ativar', [EdicaoController::class, 'ativar'])
    ->name('edicoes.ativar');

Route::patch('edicoes/{edicao}/encerrar', [EdicaoController::class, 'encerrar'])
    ->name('edicoes.encerrar');

Route::resource('locais', LocalController::class)
    ->parameters(['locais' => 'local']);

Route::resource('atividades', AtividadeController::class)
    ->parameters(['atividades' => 'atividade']);

Route::resource('speakers', SpeakerController::class);

Route::get(
    '/validar-certificado',
    [ValidacaoCertificadoController::class, 'index']
)
    ->middleware('throttle:30,1')
    ->name('certificados.validar');

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

Route::middleware([SomenteAdministrador::class])
    ->group(function () {
        Route::get(
            '/relatorios',
            [RelatorioController::class, 'index']
        )->name('relatorios.index');

        Route::get(
            '/relatorios/inscricoes',
            [RelatorioController::class, 'inscricoes']
        )->name('relatorios.inscricoes');

        Route::get(
            '/relatorios/atividades',
            [RelatorioController::class, 'atividades']
        )->name('relatorios.atividades');

        Route::get(
            '/relatorios/presencas',
            [RelatorioController::class, 'presencas']
        )->name('relatorios.presencas');

        Route::get(
            '/relatorios/carga-horaria',
            [RelatorioController::class, 'cargaHoraria']
        )->name('relatorios.carga-horaria');

        Route::get(
            '/relatorios/certificados',
            [RelatorioController::class, 'certificados']
        )->name('relatorios.certificados');

        Route::get(
            '/relatorios/ocupacao',
            [RelatorioController::class, 'ocupacao']
        )->name('relatorios.ocupacao');
    });

Route::post(
    '/meus-certificados/{inscricao}/emitir',
    [HistoricoCertificadoController::class, 'emitir']
)
    ->middleware('auth')
    ->name('certificados.historico.emitir');