@extends('layouts.app')

@section('title', 'Ajouter un employé')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
           <button onclick="window.location='{{route('users.index')}}'" class="items-center px-4 py-2 bg-blue-600 inline-flex text-white rounded hover:bg-blue-800 mb-4">
                Retour à la liste 
                <i class="fas fa-arrow-left mr-2"></i>

           </button>

            <h1 class="text-3xl font-bold text-gray-800">Ajouter un nouvel employé</h1>
            <p class="text-gray-600 mt-2">Créez un nouveau compte pour un membre de votre équipe</p>
        </div>

        <!-- Carte du formulaire -->
        <div class="glass-card rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-user-plus mr-2 text-green-500"></i>
                    Informations du nouvel employé
                </h2>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Nom -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                               placeholder="Entrez le nom complet">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror"
                               placeholder="Entrez l'adresse email">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Rôle -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-tag text-gray-400"></i>
                        </div>
                        <select id="role" 
                                name="role"
                                required
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('role') border-red-500 @enderror">
                            <option value="">Sélectionnez un rôle</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="magasinier" {{ old('role') == 'magasinier' ? 'selected' : '' }}>Magasinier</option>
                            <option value="caissier" {{ old('role') == 'caissier' ? 'selected' : '' }}>Caissier</option>
                        </select>
                    </div>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Section mot de passe -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-4 py-3 rounded-lg mb-4">
                        <h3 class="text-lg font-medium text-gray-800 flex items-center">
                            <i class="fas fa-key mr-2 text-blue-500"></i>
                            Mot de passe
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Définissez un mot de passe sécurisé pour le nouvel employé</p>
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       required
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('password') border-red-500 @enderror"
                                       placeholder="Nouveau mot de passe">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmation</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="Confirmer le mot de passe">
                            </div>
                        </div>
                    </div>

                    <!-- Indications mot de passe -->
                    <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-700 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Le mot de passe doit contenir au moins 8 caractères avec des chiffres et des lettres
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-end pt-6 border-t border-gray-200 gap-4">
                    <a href="{{ route('users.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center order-2 sm:order-1">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-3 rounded-lg shadow hover:from-green-700 hover:to-blue-700 transition-all duration-300 flex items-center justify-center order-1 sm:order-2">
                        <i class="fas fa-user-plus mr-2"></i>
                        Créer l'employé
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endpush
@endsection