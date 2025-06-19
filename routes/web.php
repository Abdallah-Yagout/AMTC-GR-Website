<?php

use App\Http\Controllers\ForumController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use Livewire\Livewire;

Route::middleware([SetLocale::class])->group(function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/community', [\App\Http\Controllers\ForumController::class, 'index'])->name('forum.index');
    Route::get('/tournament', [\App\Http\Controllers\TournamentController::class, 'index'])->name('tournament.index');
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/tournament/apply/{id}', [\App\Http\Controllers\TournamentController::class, 'apply'])->name('tournament.apply');
    Route::post('/tournament/submit', [\App\Http\Controllers\TournamentController::class, 'submit'])->name('tournament.submit');
    Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/view/{slug}', [\App\Http\Controllers\NewsController::class, 'view'])->name('news.view');
    Route::get('language/{locale}', [\App\Http\Controllers\HomeController::class, 'switchLanguage'])->name('language.switch');
    Route::post('/forums/{forum}/upvote', [ForumController::class, 'toggleUpvote']);

});



