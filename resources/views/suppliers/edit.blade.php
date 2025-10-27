@extends('layouts.app')

@section('title', 'Ajouter Fournisseur')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <button onclick="window.location='{{ route('suppliers.index') }}'" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white
                font-semibold rounded hover:bg-orange-800 mb-4">
                Retour a la liste
            </button>
            <h1 class="text-3xl font-bold text-gray-800">Ajouter un nouveau fournisseur</h1>
            <p class="text-gray-600 mt-2">Renseignez les informations du nouveau partenaire fournisseur</p>
        </div>

        <!-- Carte du formulaire -->
        <div class="glass-card rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-truck-loading mr-2 text-orange-500"></i>
                    Informations du fournisseur
                </h2>
            </div>

            <form method="POST" action="{{ route('suppliers.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Nom -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du fournisseur *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-building text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                               placeholder="Entrez le nom de l'entreprise">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Contact -->
                <div>
                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">
                        Personne à contacter
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-tie text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="contact" 
                               name="contact" 
                               value="{{ old('contact') }}" 
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('contact') border-red-500 @enderror"
                               placeholder="Nom du responsable">
                    </div>
                    @error('contact')
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
                               value="{{ old('phone') }}" 
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('phone') border-red-500 @enderror"
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
                                Le contact et le téléphone sont facultatifs, mais recommandés pour une meilleure communication avec votre fournisseur.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-end pt-6 border-t border-gray-200 gap-4">
                    <a href="{{ route('suppliers.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center order-2 sm:order-1">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-orange-600 to-amber-600 text-white px-6 py-3 rounded-lg shadow hover:from-orange-700 hover:to-amber-700 transition-all duration-300 flex items-center justify-center order-1 sm:order-2">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer le fournisseur
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Card -->
        <div class="mt-8 glass-card rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-eye mr-2 text-gray-500"></i>
                Aperçu de la fiche fournisseur
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Nom</p>
                    <p id="preview-name" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-gray-600">Contact</p>
                    <p id="preview-contact" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-gray-600">Téléphone</p>
                    <p id="preview-phone" class="font-medium text-gray-800">-</p>
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
        const contactInput = document.getElementById('contact');
        const phoneInput = document.getElementById('phone');
        
        const previewName = document.getElementById('preview-name');
        const previewContact = document.getElementById('preview-contact');
        const previewPhone = document.getElementById('preview-phone');

        function updatePreview() {
            previewName.textContent = nameInput.value || '-';
            previewContact.textContent = contactInput.value || '-';
            previewPhone.textContent = phoneInput.value || '-';
        }

        nameInput.addEventListener('input', updatePreview);
        contactInput.addEventListener('input', updatePreview);
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