@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
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
                            Nom du produit <span class="text-red-500">*</span>
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

                    <!-- Price and Purchase Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Prix de vente -->
                        <div class="space-y-4">
                            <label for="sale_price" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-currency-dollar text-green-500"></i>
                                Prix de vente (CFA) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" id="sale_price" name="sale_price" value="{{ old('sale_price') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 pl-12 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-50 transition-all duration-200 text-gray-700 placeholder-gray-400 text-lg
                                              @error('sale_price') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror"
                                       placeholder="0.00" required>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                    <span class="text-gray-500 font-semibold">CFA</span>
                                </div>
                            </div>
                            @error('sale_price')
                                <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Prix d'achat -->
                        <div class="space-y-4">
                            <label for="purchase_price" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-cash-stack text-yellow-500"></i>
                                Prix d'achat (CFA) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 pl-12 focus:outline-none focus:border-yellow-500 focus:ring-4 focus:ring-yellow-50 transition-all duration-200 text-gray-700 placeholder-gray-400 text-lg
                                              @error('purchase_price') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror"
                                       placeholder="0.00" required>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                    <span class="text-gray-500 font-semibold">CFA</span>
                                </div>
                            </div>
                            @error('purchase_price')
                                <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="space-y-4">
                        <label for="stock" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-box-seam text-purple-500"></i>
                            Stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-50 transition-all duration-200 text-gray-700 placeholder-gray-400 text-lg
                                      @error('stock') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror"
                               placeholder="Quantité en stock..." required min="0">
                        @error('stock')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div class="space-y-4">
                        <label for="category_id" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-folder text-indigo-500"></i>
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="category_id" name="category_id"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all duration-200 text-gray-700 text-lg appearance-none
                                           @error('category_id') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror" required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} - {{ $category->sub_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="bi bi-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Fournisseur -->
                    <div class="space-y-4">
                        <label for="supplier_id" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-truck text-orange-500"></i>
                            Fournisseur <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="supplier_id" name="supplier_id"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-50 transition-all duration-200 text-gray-700 text-lg appearance-none
                                           @error('supplier_id') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror" required>
                                <option value="">Sélectionnez un fournisseur</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="bi bi-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('supplier_id')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-4">
                        <label for="description" class="block text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-text-paragraph text-gray-500"></i>
                            Description
                            <span class="text-sm font-normal text-gray-500">(optionnelle)</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:border-gray-400 focus:ring-4 focus:ring-gray-50 transition-all duration-200 text-gray-700 placeholder-gray-400 text-lg resize-none
                                         @error('description') border-red-500 focus:border-red-500 focus:ring-red-50 @enderror"
                                  placeholder="Décrivez votre produit...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="bi bi-eye text-blue-600"></i>
                            Aperçu du produit
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nom</p>
                                <p id="previewName" class="font-semibold text-gray-800">
                                    {{ old('name', 'Nom du produit') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Prix de vente</p>
                                <p id="previewSalePrice" class="font-semibold text-green-600">
                                    {{ old('sale_price', '0') }} CFA
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Stock</p>
                                <p id="previewStock" class="font-semibold text-purple-600">
                                    {{ old('stock', '0') }} unités
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between gap-4 pt-8 border-t border-gray-100">
                        <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 order-2 sm:order-1">
                            <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-300"></i>
                            <span>Retour</span>
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 order-1 sm:order-2">
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
                        <li>• Sélectionnez la catégorie et le fournisseur appropriés</li>
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
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.3s ease-out; }

/* Inputs number : hide spin buttons */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type="number"] { -moz-appearance: textfield; }

/* Style pour les selects */
select { background-image: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mise à jour de l'aperçu en temps réel
    const nameInput = document.getElementById('name');
    const salePriceInput = document.getElementById('sale_price');
    const stockInput = document.getElementById('stock');
    const previewName = document.getElementById('previewName');
    const previewSalePrice = document.getElementById('previewSalePrice');
    const previewStock = document.getElementById('previewStock');

    function updatePreview() {
        previewName.textContent = nameInput.value || 'Nom du produit';
        previewSalePrice.textContent = (salePriceInput.value || '0') + ' CFA';
        previewStock.textContent = (stockInput.value || '0') + ' unités';
    }

    // Écouter les changements
    nameInput.addEventListener('input', updatePreview);
    salePriceInput.addEventListener('input', updatePreview);
    stockInput.addEventListener('input', updatePreview);

    // Effets d'animation sur les champs
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() { 
            this.parentElement.classList.add('transform', 'scale-[1.02]'); 
        });
        input.addEventListener('blur', function() { 
            this.parentElement.classList.remove('transform', 'scale-[1.02]'); 
        });
    });

    // Initialiser l'aperçu
    updatePreview();
});
</script>
@endsection