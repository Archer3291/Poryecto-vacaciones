<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $admission_date; // No necesitas establecer un valor por defecto aquí

    public function mount()
    {
        $this->admission_date = now()->toDateString(); // Establece la fecha actual
    }

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'admission_date' => 'required',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))->assignRole('employee')));

        //auth()->login($user);

        flash()
            ->translate('es')
            ->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-right',
            ])
            ->addSuccess('Usuario ingresado correctamente.', 'Bienvenido!');

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="admission_date" :value="__('Date')" />
            <x-text-input wire:model="admission_date" value="{{ now()->format('Y-m-d') }}" id="admission_date"
                class="block mt-1 w-full" type="date" name="admission_date" required autocomplete="username"
                disabled />
            <x-input-error :messages="$errors->get('date')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4" id="send">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>

<script>
    const name = document.getElementById("name")
    const email = document.getElementById("email")
    const password = document.getElementById("password")
    const password_confirmation = document.getElementById("password_confirmation")
    const send = document.getElementById("send");
    
    if (name.trim() && email.trim && password !== '') {
        send.addEventListener("click", e => {
            send.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
            <defs>
                <filter id="IconifyId18d22c094b15f27a3113">
                    <feGaussianBlur in="SourceGraphic" result="y" stdDeviation="1"/>
                    <feColorMatrix in="y" result="z" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 18 -7"/>
                    <feBlend in="SourceGraphic" in2="z"/>
                </filter>
            </defs>
            <g filter="url(#IconifyId18d22c094b15f27a3113)">
                <circle cx="5" cy="12" r="4" fill="#ffffff">
                    <animate attributeName="cx" calcMode="spline" dur="2s" keySplines=".36,.62,.43,.99;.79,0,.58,.57" repeatCount="indefinite" values="5;8;5"/>
                </circle>
                <circle cx="19" cy="12" r="4" fill="#ffffff">
                    <animate attributeName="cx" calcMode="spline" dur="2s" keySplines=".36,.62,.43,.99;.79,0,.58,.57" repeatCount="indefinite" values="19;16;19"/>
                </circle>
                <animateTransform attributeName="transform" dur="0.75s" repeatCount="indefinite" type="rotate" values="0 12 12;360 12 12"/>
            </g>
        </svg>
    `;
        });
    }
</script>
