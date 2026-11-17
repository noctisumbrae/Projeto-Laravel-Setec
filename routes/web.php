<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpeakerController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('speakers', SpeakerController::class);
