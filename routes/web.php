<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class,'index'])->name('home');
Route::get('/community', [\App\Http\Controllers\CommunityController::class,'index'])->name('community.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('language/{locale}', [App\Http\Controllers\HomeController::class, 'switchLanguage'])->name('language.switch');