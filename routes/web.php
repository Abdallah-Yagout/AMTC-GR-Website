<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ForumController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use Livewire\Livewire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

Route::middleware([SetLocale::class])->group(function () {
    // Main routes
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/tournament', [\App\Http\Controllers\TournamentController::class, 'index'])->name('tournament.index');
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/view/{slug}', [\App\Http\Controllers\NewsController::class, 'view'])->name('news.view');
    Route::get('language/{locale}', [\App\Http\Controllers\HomeController::class, 'switchLanguage'])->name('language.switch');

    // Tournament routes
    Route::middleware('auth')->group(function () {
        Route::get('/tournament/apply/{id}', [\App\Http\Controllers\TournamentController::class, 'apply'])->name('tournament.apply');
        Route::post('/tournament/submit', [\App\Http\Controllers\TournamentController::class, 'submit'])->name('tournament.submit');
    });

    // Forum routes - RESTful structure
    Route::prefix('forum')->group(function () {
        Route::get('/', [ForumController::class, 'index'])->name('forum.index');
        Route::post('/', [ForumController::class, 'store'])->name('forum.store');
        Route::get('/{forum}/edit', [ForumController::class, 'edit'])
            ->name('forum.edit')
            ->middleware('auth');
        // Individual forum post routes
        Route::prefix('{forum}')->group(function () {
            Route::get('/', [ForumController::class, 'show'])->name('forum.show');
            Route::put('/', [ForumController::class, 'update'])->name('forum.update');
            Route::delete('/', [ForumController::class, 'destroy'])->name('forum.destroy');
            Route::post('/upvote', [ForumController::class, 'toggleUpvote'])->name('forum.upvote');

            // Comments routes
            Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
            Route::get('/comments', [CommentController::class, 'loadMoreComments'])->name('forum.comments.load');
        });
    });

    // Comment deletion
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->middleware('auth')
        ->name('comments.destroy');

    // Password reset
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
