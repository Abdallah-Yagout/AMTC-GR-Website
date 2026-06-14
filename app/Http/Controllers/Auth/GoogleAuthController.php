<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OAuthSetting;
use App\Models\Profile;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Socialite does not expose fluent clientId()/clientSecret(); build the provider with config (same as services.google).
     */
    protected function googleProvider(?bool $forceVerifySsl = null): AbstractProvider
    {
        $config = OAuthSetting::googleConfig();
        $verifySsl = is_bool($forceVerifySsl)
            ? $forceVerifySsl
            : (bool) config('services.google.verify_ssl', true);

        $provider = Socialite::buildProvider(GoogleProvider::class, [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect' => $config['redirect'],
        ]);

        $provider->setHttpClient(new Client([
            'verify' => $verifySsl,
            'timeout' => 20,
        ]));

        return $provider;
    }

    public function redirect(): RedirectResponse|SymfonyRedirect
    {
        if (! OAuthSetting::isGoogleLoginConfigured()) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Google login is not configured.')]);
        }

        return $this->googleProvider()->redirect();
    }

    public function callback(): RedirectResponse
    {
        if (! OAuthSetting::isGoogleLoginConfigured()) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Google login is not configured.')]);
        }

        try {
            $googleUser = $this->googleProvider()->user();
        } catch (InvalidStateException $e) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Google sign-in session expired. Please try again.')]);
        } catch (Throwable $e) {
            // Windows local PHP sometimes misses CA bundle and throws cURL error 60.
            // Retry once without SSL verification ONLY in local environment.
            if ($this->shouldRetryWithoutSslVerification($e)) {
                try {
                    $googleUser = $this->googleProvider(false)->user();
                } catch (Throwable $retryError) {
                    report($retryError);

                    return redirect()->route('login')
                        ->withErrors(['email' => __('Unable to sign in with Google. Please try again.')]);
                }
            } else {
                report($e);

                return redirect()->route('login')
                    ->withErrors(['email' => __('Unable to sign in with Google. Please try again.')]);
            }
        }

        $email = $googleUser->getEmail();
        $googleId = $googleUser->getId();
        $name = $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Google user');

        if (! $email || ! $googleId) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Google did not return a valid email address.')]);
        }

        $user = User::query()->where('google_id', $googleId)->first();

        if (! $user) {
            $user = User::query()->where('email', $email)->first();

            if ($user) {
                $user->forceFill([
                    'google_id' => $googleId,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();
            } else {
                $user = User::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => null,
                    'email_verified_at' => now(),
                ]);

                Profile::query()->firstOrCreate(
                    ['user_id' => $user->id],
                    []
                );
            }
        }

        Auth::login($user, true);

        return redirect()->intended(config('fortify.home', '/'));
    }

    private function shouldRetryWithoutSslVerification(Throwable $e): bool
    {
        if (! app()->environment('local')) {
            return false;
        }

        return str_contains($e->getMessage(), 'cURL error 60')
            || str_contains($e->getMessage(), 'SSL certificate problem');
    }
}
