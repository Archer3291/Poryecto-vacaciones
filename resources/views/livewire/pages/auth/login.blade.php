<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Rule(['required', 'string', 'email'])]
    public string $email = '';

    #[Rule(['required', 'string'])]
    public string $password = '';

    #[Rule(['boolean'])]
    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!auth()->attempt($this->only(['email', 'password'], $this->remember))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        session()->regenerate();

        flash()
            ->translate('es')
            ->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-right',
            ])
            ->addSuccess('Haz ingresado correctamente.', 'Bienvenido ' . auth()->user()->name . '!');

        $this->redirect(session('url.intended', RouteServiceProvider::HOME), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex justify-between items-center">
            <div class="block mt-4">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="remember" id="remember" type="checkbox"
                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <button type="button" class="bg-gray-200 p-1 rounded-md hover:bg-gray-300 duration-100 ease-in-out"
                id="see-password">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <defs>
                        <clipPath id="IconifyId18d236760b6b0ca2d174">
                            <rect width="24" height="12" />
                        </clipPath>
                        <symbol id="IconifyId18d236760b6b0ca2d175">
                            <path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z"
                                clip-path="url(#IconifyId18d236760b6b0ca2d174)">
                                <animate attributeName="d" dur="6s" keyTimes="0;0.07;0.93;1"
                                    repeatCount="indefinite"
                                    values="M23 16.5C23 11.5 18.0751 12 12 12C5.92487 12 1 11.5 1 16.5z;M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z;M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z;M23 16.5C23 11.5 18.0751 12 12 12C5.92487 12 1 11.5 1 16.5z" />
                            </path>
                        </symbol>
                        <mask id="IconifyId18d236760b6b0ca2d176">
                            <use href="#IconifyId18d236760b6b0ca2d175" />
                            <use href="#IconifyId18d236760b6b0ca2d175" transform="rotate(180 12 12)" />
                            <circle cx="12" cy="12" r="0" fill="#fff">
                                <animate attributeName="r" dur="6s" keyTimes="0;0.03;0.97;1"
                                    repeatCount="indefinite" values="0;3;3;0" />
                            </circle>
                            <g fill="none" stroke-dasharray="26" stroke-dashoffset="26" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" transform="rotate(45 13 12)">
                                <path stroke="#000" d="M0 11h24" />
                                <path stroke="#fff" d="M0 13h22">
                                    <animate attributeName="d" dur="6s" repeatCount="indefinite"
                                        values="M0 13h22;M2 13h22;M0 13h22" />
                                </path>
                                <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.6s" dur="0.2s"
                                    values="26;0" />
                            </g>
                        </mask>
                    </defs>
                    <rect width="24" height="24" fill="#000000" mask="url(#IconifyId18d236760b6b0ca2d176)" />
                </svg>
            </button>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('register') }}" wire:navigate>
                    {{ __('Register') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>
<script>
    const seePassword = document.getElementById('see-password')
    const password = document.getElementById('password')

    seePassword.addEventListener('click', () => {
        if (password.type === 'password') {
            password.type = 'text'
            seePassword.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <defs>
                        <clipPath id="IconifyId18d236760b6b0ca2d180">
                            <rect width="24" height="12" />
                        </clipPath>
                        <symbol id="IconifyId18d236760b6b0ca2d181">
                            <path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z"
                                clip-path="url(#IconifyId18d236760b6b0ca2d180)">
                                <animate attributeName="d" dur="6s" keyTimes="0;0.07;0.93;1"
                                    repeatCount="indefinite"
                                    values="M23 16.5C23 11.5 18.0751 12 12 12C5.92487 12 1 11.5 1 16.5z;M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z;M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z;M23 16.5C23 11.5 18.0751 12 12 12C5.92487 12 1 11.5 1 16.5z" />
                            </path>
                        </symbol>
                        <mask id="IconifyId18d236760b6b0ca2d182">
                            <use href="#IconifyId18d236760b6b0ca2d181" />
                            <use href="#IconifyId18d236760b6b0ca2d181" transform="rotate(180 12 12)" />
                            <circle cx="12" cy="12" r="0" fill="#fff">
                                <animate attributeName="r" dur="6s" keyTimes="0;0.03;0.97;1"
                                    repeatCount="indefinite" values="0;3;3;0" />
                            </circle>
                        </mask>
                    </defs>
                    <rect width="24" height="24" fill="#000000" mask="url(#IconifyId18d236760b6b0ca2d182)" />
                </svg>
            `

        } else {
            password.type = 'password'
            seePassword.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <defs>
                        <clipPath id="IconifyId18d236760b6b0ca2d174">
                            <rect width="24" height="12" />
                        </clipPath>
                        <symbol id="IconifyId18d236760b6b0ca2d175">
                            <path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z"
                                clip-path="url(#IconifyId18d236760b6b0ca2d174)">
                                <animate attributeName="d" dur="6s" keyTimes="0;0.07;0.93;1"
                                    repeatCount="indefinite"
                                    values="M23 16.5C23 11.5 18.0751 12 12 12C5.92487 12 1 11.5 1 16.5z;M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z;M23 16.5C23 10.4249 18.0751 5.5 12 5.5C5.92487 5.5 1 10.4249 1 16.5z;M23 16.5C23 11.5 18.0751 12 12 12C5.92487 12 1 11.5 1 16.5z" />
                            </path>
                        </symbol>
                        <mask id="IconifyId18d236760b6b0ca2d176">
                            <use href="#IconifyId18d236760b6b0ca2d175" />
                            <use href="#IconifyId18d236760b6b0ca2d175" transform="rotate(180 12 12)" />
                            <circle cx="12" cy="12" r="0" fill="#fff">
                                <animate attributeName="r" dur="6s" keyTimes="0;0.03;0.97;1"
                                    repeatCount="indefinite" values="0;3;3;0" />
                            </circle>
                            <g fill="none" stroke-dasharray="26" stroke-dashoffset="26" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" transform="rotate(45 13 12)">
                                <path stroke="#000" d="M0 11h24" />
                                <path stroke="#fff" d="M0 13h22">
                                    <animate attributeName="d" dur="6s" repeatCount="indefinite"
                                        values="M0 13h22;M2 13h22;M0 13h22" />
                                </path>
                                <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.6s" dur="0.2s"
                                    values="26;0" />
                            </g>
                        </mask>
                    </defs>
                    <rect width="24" height="24" fill="#000000" mask="url(#IconifyId18d236760b6b0ca2d176)" />
                </svg>
                `
        }
    });
</script>
