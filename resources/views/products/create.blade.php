@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-2xl">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                        <span class="text-4xl">➕</span>
                        Ajouter un nouveau produit
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Remplissez les informations pour créer un nouveau produit</p>
                </div>
                <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                    <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-300"></i>
                    <span>Retour aux produits</span>
                </a>
            </div>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-2xl text-red-600"></i>
                    <div>
                        <p class="font-semibold">Erreurs de validation!</p>
                        <ul class="list-disc list-inside text-sm mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button class="absolute top-4 right-4 text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-8">
                <form action="{{ route('products.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Product Name -->
                    <div class="space-y-4">
                        <label for="name" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-tag text-blue-500"></i>
                            Nom du produit
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all duration-200 text-gray-700 placeholder-gray-400 text-lg
                                      @error('name') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror" 
                               placeholder="Entrez le nom du produit..." required>
                        @error('name')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Price and Stock -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label for="price" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-currency-dollar text-green-500"></i>
                                Prix (CFA)
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 pl-12 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-50 transition-all duration-200 text-gray-700 placeholder-gray-400 text-lg
                                              @error('price') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror"
                                       placeholder="0.00" required>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                    <span class="text-gray-500 font-semibold">CFA</span>
                                </div>
                            </div>
                            @error('price')
                                <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-4">
                            <label for="stock" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-box-seam text-purple-500"></i>
                                Stock
                            </label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock') }}"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-50 transition-all duration-200 text-gray-700 placeholder-gray-400 text-lg
                                          @error('stock') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror"
                                   placeholder="Quantité en stock..." required>
                            @error('stock')
                                <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-4">
                        <label for="description" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-text-paragraph text-orange-500"></i>
                            Description
                            <span class="text-sm font-normal text-gray-500">(optionnelle)</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-50 transition-all duration-200 text-gray-700 placeholder-gray-400 text-lg resize-none
                                         @error('description') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror"
                                  placeholder="Décrivez votre produit...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between gap-4 pt-8 border-t border-gray-100">
                        <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group order-2 sm:order-1">
                            <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-300"></i>
                            <span>Retour</span>
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group order-1 sm:order-2">
                            <i class="bi bi-plus-circle text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                            <span class="text-lg">Créer le produit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
            <div class="flex items-start gap-3">
                <i class="bi bi-lightbulb text-2xl text-blue-500 mt-1"></i>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">Conseils de création</h3>
                    <ul class="text-blue-700 text-sm space-y-1">
                        <li>• Utilisez un nom clair et descriptif pour votre produit</li>
                        <li>• Le prix doit être en Francs CFA (FCFA)</li>
                        <li>• Mettez à jour régulièrement le stock pour une gestion optimale</li>
                        <li>• Une description détaillée aide à mieux identifier le produit</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

/* Style personnalisé pour les inputs number */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>

<script>
// Animation pour les champs au focus
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('transform', 'scale-[1.02]');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('transform', 'scale-[1.02]');
        });
    });
});
</script>
@endsection