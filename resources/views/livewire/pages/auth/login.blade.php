<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 p-6">

    <div class="w-full max-w-md p-8 animate-fadeIn">

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">

            <div class="text-center mb-8 select-none">
                <h2 class="text-3xl font-bold text-white">Connexion</h2>
                <p class="text-gray-400 mt-1 text-sm">Accédez à votre espace sécurisé</p>
            </div>

            <!-- Email -->
            <div>
                <label class="text-gray-300 text-sm mb-1 block">Email</label>
                <input
                    wire:model="form.email"
                    type="email"
                    id="email"
                    class="w-full px-4 py-3 bg-gray-900/60 border border-gray-700 rounded-xl
                           text-gray-200 placeholder-gray-500
                           focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="exemple@mail.com"
                >
                <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label class="text-gray-300 text-sm mb-1 block">Mot de passe</label>
                <input
                    wire:model="form.password"
                    type="password"
                    id="password"
                    class="w-full px-4 py-3 bg-gray-900/60 border border-gray-700 rounded-xl
                           text-gray-200 placeholder-gray-500
                           focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="••••••••"
                >
                <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
            </div>

            <!-- Remember + Password Reset -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model="form.remember"
                        class="text-indigo-600 bg-gray-900 border-gray-700 rounded focus:ring-indigo-500"
                    >
                    <span class="text-gray-400 text-sm">Se souvenir de moi</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                        class="text-indigo-400 text-sm hover:text-indigo-300 transition hover:underline">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <button
                type="submit"
                class="w-full py-3 mt-2 bg-indigo-600 hover:bg-indigo-700
                       text-white font-semibold rounded-xl shadow-md transition active:scale-[0.98]">
                Connexion
            </button>

        </form>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
    </style>
</div>
