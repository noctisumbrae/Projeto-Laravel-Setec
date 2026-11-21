<?php

use App\Http\Controllers\EdicaoController;
use App\Http\Controllers\SpeakerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('edicoes', EdicaoController::class)
    ->parameters(['edicoes' => 'edicao'])
    ->except(['destroy']);

Route::patch('edicoes/{edicao}/ativar', [EdicaoController::class, 'ativar'])->name('edicoes.ativar');
Route::patch('edicoes/{edicao}/encerrar', [EdicaoController::class, 'encerrar'])->name('edicoes.encerrar');

Route::get('edicoes/{edicao}/regras-inscricao', [EdicaoController::class, 'regrasInscricao'])
    ->name('edicoes.regras-inscricao');
Route::put('edicoes/{edicao}/regras-inscricao', [EdicaoController::class, 'atualizarRegrasInscricao'])
    ->name('edicoes.regras-inscricao.atualizar');
