@extends('layouts.app')

@section('title', 'Modifier Client')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 mb-4">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste des clients
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Modifier le client</h1>
            <p class="text-gray-600 mt-2">Mettez à jour les informations de {{ $client->name }}</p>
        </div>

        <!-- Carte du formulaire -->
        <div class="glass-card rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-user-edit mr-2 text-purple-500"></i>
                    Informations du client
                </h2>
            </div>

            <form method="POST" action="{{ route('clients.update', $client->id) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Nom -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $client->name) }}" 
                               required
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                               placeholder="Entrez le nom complet du client">
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
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $client->email) }}" 
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('email') border-red-500 @enderror"
                               placeholder="email@exemple.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Numéro de téléphone
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $client->phone) }}" 
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('phone') border-red-500 @enderror"
                               placeholder="+33 1 23 45 67 89">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Informations supplémentaires -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm text-blue-800 font-medium">Champs facultatifs</p>
                            <p class="text-sm text-blue-600 mt-1">
                                L'email et le téléphone sont facultatifs, mais recommandés pour une meilleure gestion de votre relation client.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-end pt-6 border-t border-gray-200 gap-4">
                    <a href="{{ route('clients.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center order-2 sm:order-1">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-lg shadow hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 flex items-center justify-center order-1 sm:order-2">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Card -->
        <div class="mt-8 glass-card rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-eye mr-2 text-gray-500"></i>
                Aperçu de la fiche client
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Nom</p>
                    <p id="preview-name" class="font-medium text-gray-800">{{ $client->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Email</p>
                    <p id="preview-email" class="font-medium text-gray-800">{{ $client->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Téléphone</p>
                    <p id="preview-phone" class="font-medium text-gray-800">{{ $client->phone ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview en temps réel
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        
        const previewName = document.getElementById('preview-name');
        const previewEmail = document.getElementById('preview-email');
        const previewPhone = document.getElementById('preview-phone');

        function updatePreview() {
            previewName.textContent = nameInput.value || '-';
            previewEmail.textContent = emailInput.value || '-';
            previewPhone.textContent = phoneInput.value || '-';
        }

        nameInput.addEventListener('input', updatePreview);
        emailInput.addEventListener('input', updatePreview);
        phoneInput.addEventListener('input', updatePreview);

        // Initial update
        updatePreview();
    });
</script>
@endpush

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