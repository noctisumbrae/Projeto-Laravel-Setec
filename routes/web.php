<?php

use App\Http\Controllers\EdicaoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalController;

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