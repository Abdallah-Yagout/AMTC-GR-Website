<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\Games\MatchingGameController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\GamesLeaderboardController;
use App\Http\Controllers\GRCarsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileEngagementController;
use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::middleware([SetLocale::class])->group(function () {
    // Main routes
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/tournament', [\App\Http\Controllers\TournamentController::class, 'index'])->name('tournament.index');
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/view/{slug}', [\App\Http\Controllers\NewsController::class, 'view'])->name('news.view');
    Route::get('/games', [GamesController::class, 'index'])->name('games.index');
    Route::get('/games/matching', [MatchingGameController::class, 'show'])->name('games.matching');
    Route::get('/games/leaderboard', [GamesLeaderboardController::class, 'index'])->name('games.leaderboard');
    Route::get('/gr-cars', [GRCarsController::class, 'index'])->name('gr-cars.index');
    Route::get('language/{locale}', [\App\Http\Controllers\HomeController::class, 'switchLanguage'])->name('language.switch');

    Route::get('contact', [\App\Http\Controllers\ContactController::class, 'show'])->name('contact.index');
    Route::post('contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
    Route::post('/chatbot/message', [ChatbotController::class, 'message'])
        ->middleware('throttle:30,1')
        ->name('chatbot.message');

    // Tournament routes
    Route::middleware('auth')->group(function () {
        Route::get('/tournament/apply/{id}', [\App\Http\Controllers\TournamentController::class, 'apply'])->name('tournament.apply');
        Route::post('/tournament/submit', [\App\Http\Controllers\TournamentController::class, 'submit'])->name('tournament.submit');
        Route::post('/games/claim-daily', [GamesController::class, 'claimDaily'])->name('games.claim-daily');
        Route::post('/games/matching/start', [MatchingGameController::class, 'start'])
            ->middleware('throttle:30,1')
            ->name('games.matching.start');
        Route::post('/games/matching/complete', [MatchingGameController::class, 'complete'])
            ->middleware('throttle:45,1')
            ->name('games.matching.complete');
    });
    $profileAuthMiddleware = config('jetstream.guard')
        ? 'auth:'.config('jetstream.guard')
        : 'auth';

    Route::middleware(array_values(array_filter([
        $profileAuthMiddleware,
        config('jetstream.auth_session'),
        'verified',
    ])))->group(function () {
        Route::get('/user/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::post('/user/profile/engagement/check-in', [ProfileEngagementController::class, 'claimCheckIn'])
            ->name('profile.engagement.check-in');
    });

    Route::get('contact', [\App\Http\Controllers\ContactController::class, 'show'])->name('contact.index');
    Route::post('contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

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
