@extends('layouts.app')

@section('title', 'Modifier le produit : ' . $product->name)

@section('styles')
<style>
    .form-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: calc(100vh - 64px);
    }
    
    .profit-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .profit-positive {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .profit-negative {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .profit-neutral {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .input-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
    }
    
    .animated-input:focus + .input-icon {
        color: #3b82f6;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .card-hover {
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #3b82f6;
    }
</style>
@endsection

@section('content')
<div class="form-container py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header avec statistiques -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                        <i class="bi bi-pencil-square text-3xl text-blue-600"></i>
                        Modifier le produit
                    </h1>
                    <p class="text-gray-600 mt-2 flex items-center gap-2">
                        <span class="font-semibold text-blue-700">{{ $product->name }}</span>
                        • ID: <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-sm font-medium">{{ $product->id }}</span>
                    </p>
                </div>
                
                <!-- Bouton Retour -->
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 group">
                    <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Retour à la liste</span>
                </a>
            </div>
            
            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Marge bénéficiaire</p>
                            <p class="text-xl font-bold text-gray-800 mt-1">
                                @if($product->sale_price && $product->purchase_price)
                                    {{ number_format($product->sale_price - $product->purchase_price, 0, ',', ' ') }} CFA
                                @else
                                    0 CFA
                                @endif
                            </p>
                        </div>
                        <div class="text-2xl {{ $product->sale_price > $product->purchase_price ? 'text-green-500' : 'text-red-500' }}">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Stock actuel</p>
                            <p class="text-xl font-bold {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }} mt-1">
                                {{ $product->stock }} unités
                            </p>
                        </div>
                        <div class="text-2xl {{ $product->stock > 10 ? 'text-green-500' : ($product->stock > 0 ? 'text-yellow-500' : 'text-red-500') }}">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Valeur du stock</p>
                            <p class="text-xl font-bold text-gray-800 mt-1">
                                {{ number_format($product->sale_price * $product->stock, 0, ',', ' ') }} CFA
                            </p>
                        </div>
                        <div class="text-2xl text-purple-500">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden card-hover">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="bi bi-box text-xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Informations du produit</h2>
                            <p class="text-blue-100 text-sm">Mettez à jour les détails du produit</p>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <span class="text-white/80 text-sm">Dernière mise à jour : {{ $product->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Messages d'erreur -->
            @if($errors->any())
                <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 mx-6 mt-4 p-4 rounded-lg animate-fade-in">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-exclamation-triangle-fill text-2xl text-red-600"></i>
                        <div>
                            <p class="font-semibold text-red-800">Veuillez corriger les erreurs suivantes :</p>
                            <ul class="list-disc list-inside text-red-700 text-sm mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Content -->
            <form action="{{ route('products.update', $product->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom du produit -->
                    <div class="relative">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-tag mr-2"></i>Nom du produit
                        </label>
                        <div class="relative">
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 animated-input"
                                   placeholder="Ex: Marteau professionnel 500g" required>
                            <div class="input-icon">
                                <i class="bi bi-tag"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Nom complet et descriptif du produit</p>
                    </div>

                    <!-- Prix d'achat -->
                    <div class="relative">
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-currency-dollar mr-2"></i>Prix d'achat (CFA)
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" id="purchase_price" name="purchase_price" 
                                   value="{{ old('purchase_price', $product->purchase_price) }}"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 animated-input"
                                   placeholder="0.00" required>
                            <div class="input-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Coût d'acquisition chez le fournisseur</p>
                    </div>

                    <!-- Prix de vente -->
                    <div class="relative">
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-cash-stack mr-2"></i>Prix de vente (CFA)
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" id="sale_price" name="sale_price" 
                                   value="{{ old('sale_price', $product->sale_price) }}"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 animated-input calculate-margin"
                                   placeholder="0.00" required>
                            <div class="input-icon">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                        <div id="marginDisplay" class="mt-2">
                            @php
                                $margin = $product->sale_price - $product->purchase_price;
                                $marginPercent = $product->purchase_price > 0 ? ($margin / $product->purchase_price) * 100 : 0;
                            @endphp
                            <span class="profit-badge {{ $margin > 0 ? 'profit-positive' : ($margin < 0 ? 'profit-negative' : 'profit-neutral') }}">
                                Marge: {{ number_format($margin, 0, ',', ' ') }} CFA
                                @if($marginPercent > 0)
                                    ({{ number_format($marginPercent, 1) }}%)
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="relative">
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-boxes mr-2"></i>Quantité en stock
                        </label>
                        <div class="relative">
                            <input type="number" id="stock" name="stock" min="0" 
                                   value="{{ old('stock', $product->stock) }}"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 animated-input"
                                   placeholder="0" required>
                            <div class="input-icon">
                                <i class="bi bi-boxes"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Nombre d'unités disponibles</p>
                    </div>

                    <!-- Catégorie -->
                    <div class="relative">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-folder mr-2"></i>Catégorie
                        </label>
                        <div class="relative">
                            <select id="category_id" name="category_id"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                                            data-color="{{ $category->color ?? '#6b7280' }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-icon">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="bi bi-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Fournisseur -->
                    <div class="relative">
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-truck mr-2"></i>Fournisseur
                        </label>
                        <div class="relative">
                            <select id="supplier_id" name="supplier_id"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none">
                                <option value="">Sélectionner un fournisseur</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" 
                                            {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="bi bi-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="relative">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-card-text mr-2"></i>Description détaillée
                    </label>
                    <div class="relative">
                        <textarea id="description" name="description" rows="4"
                                  class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 animated-input resize-none"
                                  placeholder="Décrivez le produit en détail...">{{ old('description', $product->description) }}</textarea>
                        <div class="absolute left-3 top-3">
                            <i class="bi bi-card-text text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500">Informations supplémentaires sur le produit</p>
                        <p class="text-xs text-gray-400" id="charCount">0/500 caractères</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 pt-6 border-t border-gray-200">
                    <div class="flex gap-3">
                        <a href="{{ route('products.show', $product->id) }}" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="bi bi-eye"></i>
                            <span>Voir le produit</span>
                        </a>
                        
                        @if(auth()->user()->role === 'admin')
                        <button type="button" 
                                onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) document.getElementById('deleteForm').submit();"
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="bi bi-trash"></i>
                            <span>Supprimer</span>
                        </button>
                        @endif
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="reset" 
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="bi bi-arrow-clockwise"></i>
                            <span>Réinitialiser</span>
                        </button>
                        
                        <button type="submit" 
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 group">
                            <i class="bi bi-save text-xl group-hover:rotate-12 transition-transform duration-300"></i>
                            <span>Enregistrer les modifications</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Form de suppression (caché) -->
        @if(auth()->user()->role === 'admin')
        <form id="deleteForm" action="{{ route('products.destroy', $product->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endif

        <!-- Aperçu en temps réel -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="bi bi-eye text-xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Aperçu du produit</h2>
                        <p class="text-purple-100 text-sm">Visualisation en temps réel</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-700">Informations produit</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Nom :</span>
                                <p id="previewName" class="font-medium text-gray-800">{{ $product->name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">Prix achat :</span>
                                    <p id="previewPurchase" class="font-medium text-gray-800">{{ number_format($product->purchase_price, 0, ',', ' ') }} CFA</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Prix vente :</span>
                                    <p id="previewSale" class="font-medium text-gray-800">{{ number_format($product->sale_price, 0, ',', ' ') }} CFA</p>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Stock :</span>
                                <p id="previewStock" class="font-medium {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $product->stock }} unités
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-700">Catégorie & Fournisseur</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Catégorie :</span>
                                <p id="previewCategory" class="font-medium text-gray-800">
                                    {{ $product->category->name ?? 'Non définie' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Fournisseur :</span>
                                <p id="previewSupplier" class="font-medium text-gray-800">
                                    {{ $product->supplier->name ?? 'Non défini' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <span class="text-sm text-gray-500">Description :</span>
                    <p id="previewDescription" class="text-gray-700 mt-2">
                        {{ $product->description ?: 'Aucune description' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mise à jour en temps réel de l'aperçu
    const updatePreview = () => {
        document.getElementById('previewName').textContent = document.getElementById('name').value || 'Non défini';
        document.getElementById('previewPurchase').textContent = formatCurrency(document.getElementById('purchase_price').value) + ' CFA';
        document.getElementById('previewSale').textContent = formatCurrency(document.getElementById('sale_price').value) + ' CFA';
        
        const stock = parseInt(document.getElementById('stock').value) || 0;
        const stockElement = document.getElementById('previewStock');
        stockElement.textContent = stock + ' unités';
        stockElement.className = 'font-medium ' + (stock > 10 ? 'text-green-600' : (stock > 0 ? 'text-yellow-600' : 'text-red-600'));
        
        const categorySelect = document.getElementById('category_id');
        const categoryText = categorySelect.options[categorySelect.selectedIndex]?.text || 'Non définie';
        document.getElementById('previewCategory').textContent = categoryText;
        
        const supplierSelect = document.getElementById('supplier_id');
        const supplierText = supplierSelect.options[supplierSelect.selectedIndex]?.text || 'Non défini';
        document.getElementById('previewSupplier').textContent = supplierText;
        
        const description = document.getElementById('description').value;
        document.getElementById('previewDescription').textContent = description || 'Aucune description';
        
        // Mettre à jour le compteur de caractères
        const charCount = description.length;
        document.getElementById('charCount').textContent = charCount + '/500 caractères';
        
        // Calculer et afficher la marge
        calculateAndDisplayMargin();
    };
    
    // Formater la devise
    const formatCurrency = (value) => {
        const num = parseFloat(value) || 0;
        return new Intl.NumberFormat('fr-FR').format(Math.round(num));
    };
    
    // Calculer et afficher la marge
    const calculateAndDisplayMargin = () => {
        const purchasePrice = parseFloat(document.getElementById('purchase_price').value) || 0;
        const salePrice = parseFloat(document.getElementById('sale_price').value) || 0;
        const margin = salePrice - purchasePrice;
        const marginPercent = purchasePrice > 0 ? (margin / purchasePrice) * 100 : 0;
        
        const marginDisplay = document.getElementById('marginDisplay');
        let badgeClass = 'profit-neutral';
        if (margin > 0) {
            badgeClass = 'profit-positive';
        } else if (margin < 0) {
            badgeClass = 'profit-negative';
        }
        
        marginDisplay.innerHTML = `
            <span class="profit-badge ${badgeClass}">
                Marge: ${formatCurrency(margin)} CFA
                ${marginPercent > 0 ? `(${marginPercent.toFixed(1)}%)` : ''}
            </span>
        `;
    };
    
    // Écouter les changements sur tous les champs
    const inputs = ['name', 'purchase_price', 'sale_price', 'stock', 'category_id', 'supplier_id', 'description'];
    inputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        }
    });
    
    // Initialiser l'aperçu
    updatePreview();
    
    // Validation en temps réel pour le prix de vente
    document.getElementById('sale_price')?.addEventListener('input', function() {
        const purchasePrice = parseFloat(document.getElementById('purchase_price').value) || 0;
        const salePrice = parseFloat(this.value) || 0;
        
        if (salePrice < purchasePrice && purchasePrice > 0) {
            this.style.borderColor = '#ef4444';
            this.style.backgroundColor = '#fef2f2';
        } else {
            this.style.borderColor = '#d1d5db';
            this.style.backgroundColor = '#f9fafb';
        }
    });
    
    // Auto-calcul de la marge quand les prix changent
    const priceInputs = document.querySelectorAll('.calculate-margin');
    priceInputs.forEach(input => {
        input.addEventListener('input', calculateAndDisplayMargin);
    });
    
    // Confirmation avant envoi
    document.querySelector('form')?.addEventListener('submit', function(e) {
        const purchasePrice = parseFloat(document.getElementById('purchase_price').value) || 0;
        const salePrice = parseFloat(document.getElementById('sale_price').value) || 0;
        
        if (salePrice < purchasePrice && purchasePrice > 0) {
            if (!confirm('⚠️ Le prix de vente est inférieur au prix d\'achat. Voulez-vous continuer ?')) {
                e.preventDefault();
                return false;
            }
        }
        
        const stock = parseInt(document.getElementById('stock').value) || 0;
        if (stock < 0) {
            alert('❌ La quantité en stock ne peut pas être négative.');
            e.preventDefault();
            return false;
        }
        
        // Petit effet de chargement
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enregistrement en cours...';
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection