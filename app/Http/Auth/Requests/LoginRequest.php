<?php

namespace App\Http\Auth\Requests;

use App\Http\FormRequest;
use App\Rules\Phone;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login'    => 'required_without_all:email,phone|string',
            'email'    => 'required_without_all:login,phone|email:strict',
            'phone'    => ['required_without_all:email,login', new Phone()],
            'password' => 'required|string',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $requestColumn = 'email';
        foreach (['login', 'email', 'phone'] as $column) {
            if ($this->{$column}) {
                $requestColumn = $column;
                break;
            }
        }

        $loginValue = $this->{$requestColumn};

        $authColumn = $this->email || filter_var($loginValue, FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'phone';

        if ($authColumn === 'phone') {
            $loginValue = preg_replace('/[^0-9]/si', '', $loginValue);
        }

        $this->ensureIsNotRateLimited($requestColumn, $loginValue);
        $attempt = [
            $authColumn => $loginValue,
            'password' => $this->password,
        ];

        if (! Auth::attempt($attempt, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($loginValue));

            throw ValidationException::withMessages([
                $requestColumn => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($loginValue));
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited($requestColumn, $loginValue)
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($loginValue), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey($loginValue));

        throw ValidationException::withMessages([
            $requestColumn => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey($loginValue)
    {
        return Str::lower($loginValue).'|'.$this->ip();
    }
}
