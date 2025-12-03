@extends('layouts.app')

@section('title', 'Nouvelle vente')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-800 to-blue-600 bg-clip-text text-transparent flex items-center gap-3">
                        <i class="bi bi-cart-plus text-4xl text-blue-600"></i>
                        Nouvelle vente
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Enregistrez une nouvelle transaction</p>
                </div>
                <a href="{{ route('sales.index') }}" 
                   class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="bi bi-arrow-left"></i>
                    <span>Retour aux ventes</span>
                </a>
            </div>
        </div>

        <!-- Formulaire de vente -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
            <form id="saleForm" action="{{ route('sales.store') }}" method="POST">
                @csrf
                
                <!-- Client -->
                <div class="mb-8">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-person-circle mr-2"></i>
                        Client (optionnel)
                    </label>
                    <select id="client_id" name="client_id" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Sélectionner un client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} - {{ $client->phone ?? 'Sans téléphone' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Produits -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="bi bi-box-seam mr-2"></i>
                            Produits
                        </h3>
                        <button type="button" id="addProduct" 
                                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-2 px-4 rounded-lg flex items-center gap-2 transition-all">
                            <i class="bi bi-plus-lg"></i>
                            Ajouter un produit
                        </button>
                    </div>

                    <div id="productsContainer">
                        <!-- Premier produit -->
                        <div class="product-row mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Produit -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Produit</label>
                                    <select name="products[0][product_id]" 
                                            class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    data-price="{{ $product->sale_price }}"
                                                    data-stock="{{ $product->stock }}">
                                                {{ $product->name }} - {{ number_format($product->sale_price, 0, ',', ' ') }} FCFA
                                                (Stock: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Quantité -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                                    <input type="number" 
                                           name="products[0][quantity]" 
                                           min="1" 
                                           value="1" 
                                           class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <div class="stock-info text-xs text-gray-500 mt-1">
                                        Stock disponible: <span class="available-stock">0</span>
                                    </div>
                                </div>
                                
                                <!-- Prix et actions -->
                                <div class="flex items-end gap-2">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix total</label>
                                        <div class="total-price-display px-3 py-2 bg-gray-100 rounded-lg text-lg font-semibold text-gray-800">
                                            0 FCFA
                                        </div>
                                    </div>
                                    <button type="button" class="remove-product text-red-500 hover:text-red-700 p-2">
                                        <i class="bi bi-trash text-xl"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Résumé -->
                <div class="mb-8 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">
                        <i class="bi bi-receipt mr-2"></i>
                        Récapitulatif de la vente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-blue-700">Nombre de produits</p>
                            <p id="productCount" class="text-2xl font-bold text-blue-800">1</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700">Quantité totale</p>
                            <p id="totalQuantity" class="text-2xl font-bold text-blue-800">0</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700">Montant total</p>
                            <p id="grandTotal" class="text-2xl font-bold text-blue-800">0 FCFA</p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <button type="reset" 
                            class="px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                        <i class="bi bi-arrow-clockwise mr-2"></i>
                        Réinitialiser
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="bi bi-check-circle mr-2"></i>
                        Enregistrer la vente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script JavaScript pour la gestion dynamique -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let productCount = 1;
    const productsContainer = document.getElementById('productsContainer');
    const addProductBtn = document.getElementById('addProduct');
    const productCountDisplay = document.getElementById('productCount');
    const totalQuantityDisplay = document.getElementById('totalQuantity');
    const grandTotalDisplay = document.getElementById('grandTotal');

    // Fonction pour ajouter un produit
    addProductBtn.addEventListener('click', function() {
        productCount++;
        
        const newProductRow = document.createElement('div');
        newProductRow.className = 'product-row mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
        newProductRow.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produit</label>
                    <select name="products[${productCount-1}][product_id]" 
                            class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un produit</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->sale_price }}"
                                    data-stock="{{ $product->stock }}">
                                {{ $product->name }} - {{ number_format($product->sale_price, 0, ',', ' ') }} FCFA
                                (Stock: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                    <input type="number" 
                           name="products[${productCount-1}][quantity]" 
                           min="1" 
                           value="1" 
                           class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <div class="stock-info text-xs text-gray-500 mt-1">
                        Stock disponible: <span class="available-stock">0</span>
                    </div>
                </div>
                
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix total</label>
                        <div class="total-price-display px-3 py-2 bg-gray-100 rounded-lg text-lg font-semibold text-gray-800">
                            0 FCFA
                        </div>
                    </div>
                    <button type="button" class="remove-product text-red-500 hover:text-red-700 p-2">
                        <i class="bi bi-trash text-xl"></i>
                    </button>
                </div>
            </div>
        `;
        
        productsContainer.appendChild(newProductRow);
        productCountDisplay.textContent = productCount;
        
        // Initialiser les événements pour la nouvelle ligne
        initProductRow(newProductRow);
        updateSummary();
    });

    // Initialiser les événements pour une ligne produit
    function initProductRow(row) {
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const totalPriceDisplay = row.querySelector('.total-price-display');
        const stockDisplay = row.querySelector('.available-stock');
        const removeBtn = row.querySelector('.remove-product');

        function updateProductRow() {
            const selectedOption = productSelect.selectedOptions[0];
            const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
            const stock = selectedOption ? parseInt(selectedOption.dataset.stock) : 0;
            const quantity = parseInt(quantityInput.value) || 0;
            
            // Mettre à jour le stock affiché
            stockDisplay.textContent = stock;
            
            // Valider la quantité
            if (quantity > stock) {
                quantityInput.classList.add('border-red-500');
                quantityInput.setCustomValidity(`Stock insuffisant. Maximum: ${stock}`);
                totalPriceDisplay.textContent = '0 FCFA';
            } else {
                quantityInput.classList.remove('border-red-500');
                quantityInput.setCustomValidity('');
                
                // Calculer et afficher le prix total
                const total = price * quantity;
                totalPriceDisplay.textContent = total.toLocaleString('fr-FR') + ' FCFA';
            }
            
            updateSummary();
        }

        // Événements
        productSelect.addEventListener('change', updateProductRow);
        quantityInput.addEventListener('input', updateProductRow);
        
        // Bouton supprimer
        removeBtn.addEventListener('click', function() {
            if (productCount > 1) {
                row.remove();
                productCount--;
                productCountDisplay.textContent = productCount;
                updateSummary();
            }
        });

        // Initialiser
        updateProductRow();
    }

    // Mettre à jour le résumé
    function updateSummary() {
        let totalQuantity = 0;
        let grandTotal = 0;
        
        document.querySelectorAll('.product-row').forEach(row => {
            const quantityInput = row.querySelector('.quantity-input');
            const productSelect = row.querySelector('.product-select');
            const selectedOption = productSelect.selectedOptions[0];
            
            if (selectedOption) {
                const price = parseFloat(selectedOption.dataset.price);
                const quantity = parseInt(quantityInput.value) || 0;
                const stock = parseInt(selectedOption.dataset.stock);
                
                if (quantity <= stock) {
                    totalQuantity += quantity;
                    grandTotal += price * quantity;
                }
            }
        });
        
        totalQuantityDisplay.textContent = totalQuantity;
        grandTotalDisplay.textContent = grandTotal.toLocaleString('fr-FR') + ' FCFA';
    }

    // Initialiser les lignes existantes
    document.querySelectorAll('.product-row').forEach(row => {
        initProductRow(row);
    });

    // Gestion de la soumission du formulaire
    document.getElementById('saleForm').addEventListener('submit', function(e) {
        let isValid = true;
        let errorMessage = '';
        
        document.querySelectorAll('.product-row').forEach((row, index) => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const selectedOption = productSelect.selectedOptions[0];
            
            if (!selectedOption) {
                isValid = false;
                errorMessage = `Veuillez sélectionner un produit pour l'article ${index + 1}`;
                productSelect.focus();
                return;
            }
            
            const stock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityInput.value) || 0;
            
            if (quantity < 1) {
                isValid = false;
                errorMessage = `La quantité doit être au moins 1 pour l'article ${index + 1}`;
                quantityInput.focus();
                return;
            }
            
            if (quantity > stock) {
                isValid = false;
                errorMessage = `Stock insuffisant pour "${selectedOption.text}". Stock disponible: ${stock}`;
                quantityInput.focus();
                return;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Erreur: ' + errorMessage);
        }
    });
});
</script>

<style>
.product-row {
    transition: all 0.3s ease;
}

.product-row:hover {
    background-color: #f9fafb;
    border-color: #3b82f6;
}

.total-price-display {
    transition: all 0.2s ease;
}

.quantity-input:invalid {
    border-color: #ef4444;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.product-row {
    animation: fadeIn 0.3s ease-out;
}
</style>
@endsection