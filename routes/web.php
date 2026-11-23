<?php

/*
|--------------------------------------------------------------------------
| Rotas da EQUIPE 2 - Participantes e inscriÃ§Ãµes
|--------------------------------------------------------------------------
| Arquivo separado para evitar conflitos de merge no Git com as outras equipes.
| O arquivo Ã© carregado por routes/web.php no projeto integrado Laravel 12.
|
*/

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscricaoAtividadeController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\ProgramacaoController;
use App\Http\Controllers\RecuperacaoSenhaController;
use Illuminate\Support\Facades\Route;

// ---------- Ãrea pÃºblica ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programacao', [ProgramacaoController::class, 'index'])->name('programacao.index');
Route::get('/programacao/{atividade}', [ProgramacaoController::class, 'show'])->name('programacao.show');

// ---------- Visitantes (nÃ£o logados) ----------
Route::middleware('guest')->group(function () {
    Route::get('/cadastro', [ParticipanteController::class, 'create'])->name('participantes.create');
    Route::post('/cadastro', [ParticipanteController::class, 'store'])->name('participantes.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

    Route::get('/esqueci-minha-senha', [RecuperacaoSenhaController::class, 'formularioEsqueci'])->name('password.request');
    Route::post('/esqueci-minha-senha', [RecuperacaoSenhaController::class, 'enviarLink'])->middleware('throttle:3,1')->name('password.email');
    // O nome "password.reset" Ã© exigido pelo e-mail padrÃ£o do Laravel.
    Route::get('/redefinir-senha/{token}', [RecuperacaoSenhaController::class, 'formularioRedefinir'])->name('password.reset');
    Route::post('/redefinir-senha', [RecuperacaoSenhaController::class, 'redefinir'])->name('password.update');
});

// ---------- Ãrea exclusiva do participante ----------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/meu-perfil', [ParticipanteController::class, 'edit'])->name('perfil.edit');
    Route::put('/meu-perfil', [ParticipanteController::class, 'update'])->name('perfil.update');

    // O parÃ¢metro precisa ser informado, pois o plural "inscricoes" nÃ£o singulariza corretamente.
    Route::resource('inscricoes', InscricaoController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->parameters(['inscricoes' => 'inscricao']);

    Route::get('/inscricoes/{inscricao}/atividades', [InscricaoAtividadeController::class, 'edit'])->name('inscricoes.atividades.edit');
    Route::put('/inscricoes/{inscricao}/atividades', [InscricaoAtividadeController::class, 'update'])->name('inscricoes.atividades.update');
});

