<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     * Try team guard first (username), then user guard (email).
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): string
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        // Logout dari semua guards dulu untuk ensure clean state
        Auth::guard('web')->logout();
        Auth::guard('team')->logout();

        // Try team guard first (using username)
        if (Auth::guard('team')->attempt(['username' => $login, 'password' => $password], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return 'team';
        }

        // Try user guard (using email)
        if (Auth::guard('web')->attempt(['email' => $login, 'password' => $password], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return 'web';
        }

        // Both failed
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => 'Username/Email atau password salah.',
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
