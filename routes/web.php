
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificadoController;

// Rota da página inicial do projeto
Route::get('/', function () {
    return view('home');
})->name('home');

// Rota do módulo de certificados - Equipe 5
Route::get('/certificados', [CertificadoController::class, 'index'])
    ->name('certificados.index');
