<?php

use App\Http\Controllers\ForumController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use Livewire\Livewire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;


Route::middleware([SetLocale::class])->group(function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/forum', [\App\Http\Controllers\ForumController::class, 'index'])->name('forum.index');
    Route::get('/tournament', [\App\Http\Controllers\TournamentController::class, 'index'])->name('tournament.index');
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/tournament/apply/{id}', [\App\Http\Controllers\TournamentController::class, 'apply'])->name('tournament.apply');
    Route::post('/tournament/submit', [\App\Http\Controllers\TournamentController::class, 'submit'])->name('tournament.submit');
    Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/view/{slug}', [\App\Http\Controllers\NewsController::class, 'view'])->name('news.view');
    Route::get('language/{locale}', [\App\Http\Controllers\HomeController::class, 'switchLanguage'])->name('language.switch');
    Route::post('/forums/{forum}/upvote', [ForumController::class, 'toggleUpvote']);
    Route::get('/forums/view/{forum}', [ForumController::class, 'view'])->name('forum.view');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    })->middleware('guest')->name('password.email');
});



