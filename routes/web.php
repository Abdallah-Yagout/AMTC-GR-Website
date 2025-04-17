<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class,'index'])->name('home')->middleware(\App\Http\Middleware\SetLocale::class);
Route::get('/community', [\App\Http\Controllers\ForumController::class,'index'])->name('forum.index')->middleware(\App\Http\Middleware\SetLocale::class);

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
