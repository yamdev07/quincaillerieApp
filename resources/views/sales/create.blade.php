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
                        <div class="product-row mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200" data-index="0">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <!-- Produit -->
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Produit</label>
                                    <select name="products[0][product_id]" 
                                            class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    data-price="{{ $product->sale_price }}"
                                                    data-stock="{{ $product->stock }}"
                                                    data-name="{{ $product->name }}">
                                                {{ $product->name }} (Stock: {{ $product->stock }}) - {{ number_format($product->sale_price, 0, ',', ' ') }} FCFA
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Quantité -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                                    <input type="number" 
                                           name="products[0][quantity]" 
                                           min="1" 
                                           value="1" 
                                           class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <div class="stock-info text-xs mt-1">
                                        <span class="text-gray-500">Stock: </span>
                                        <span class="available-stock font-semibold text-blue-600">0</span>
                                    </div>
                                </div>
                                
                                <!-- Prix unitaire -->
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire (FCFA)</label>
                                    <input type="number" 
                                           name="products[0][unit_price]" 
                                           class="unit-price-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           step="0.01"
                                           min="0"
                                           value="0">
                                </div>
                                
                                <!-- Prix total -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix total</label>
                                    <div class="total-price-display px-3 py-2 bg-green-50 rounded-lg border border-green-200 text-green-800 font-bold text-center">
                                        0 FCFA
                                    </div>
                                </div>
                                
                                <!-- Bouton supprimer -->
                                <div class="md:col-span-1">
                                    <button type="button" class="remove-product w-full text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
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
    let productRows = 1;
    const productsContainer = document.getElementById('productsContainer');
    const addProductBtn = document.getElementById('addProduct');
    const productCountDisplay = document.getElementById('productCount');
    const totalQuantityDisplay = document.getElementById('totalQuantity');
    const grandTotalDisplay = document.getElementById('grandTotal');

    // Template HTML pour une nouvelle ligne produit
    const productRowTemplate = `
        <div class="product-row mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200" data-index="{index}">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Produit -->
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produit</label>
                    <select name="products[{index}][product_id]" 
                            class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Sélectionner un produit</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->sale_price }}"
                                    data-stock="{{ $product->stock }}"
                                    data-name="{{ $product->name }}">
                                {{ $product->name }} (Stock: {{ $product->stock }}) - {{ number_format($product->sale_price, 0, ',', ' ') }} FCFA
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Quantité -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                    <input type="number" 
                           name="products[{index}][quantity]" 
                           min="1" 
                           value="1" 
                           class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <div class="stock-info text-xs mt-1">
                        <span class="text-gray-500">Stock: </span>
                        <span class="available-stock font-semibold text-blue-600">0</span>
                    </div>
                </div>
                
                <!-- Prix unitaire -->
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire (FCFA)</label>
                    <input type="number" 
                           name="products[{index}][unit_price]" 
                           class="unit-price-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           step="0.01"
                           min="0"
                           value="0">
                </div>
                
                <!-- Prix total -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix total</label>
                    <div class="total-price-display px-3 py-2 bg-green-50 rounded-lg border border-green-200 text-green-800 font-bold text-center">
                        0 FCFA
                    </div>
                </div>
                
                <!-- Bouton supprimer -->
                <div class="md:col-span-1">
                    <button type="button" class="remove-product w-full text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                        <i class="bi bi-trash text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    // Fonction pour ajouter un produit
    addProductBtn.addEventListener('click', function() {
        const newRow = productRowTemplate.replace(/{index}/g, productRows);
        productsContainer.insertAdjacentHTML('beforeend', newRow);
        
        // Initialiser la nouvelle ligne
        const newProductRow = productsContainer.lastElementChild;
        newProductRow.setAttribute('data-index', productRows);
        initProductRow(newProductRow);
        
        productRows++;
        productCountDisplay.textContent = productRows;
        updateSummary();
    });

    // Fonction pour initialiser une ligne produit
    function initProductRow(row) {
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const unitPriceInput = row.querySelector('.unit-price-input');
        const totalPriceDisplay = row.querySelector('.total-price-display');
        const stockDisplay = row.querySelector('.available-stock');
        const removeBtn = row.querySelector('.remove-product');

        // Fonction pour calculer le prix total d'un produit
        function calculateProductTotal() {
            const selectedOption = productSelect.selectedOptions[0];
            
            if (!selectedOption || !selectedOption.value) {
                // Reset si pas de produit sélectionné
                totalPriceDisplay.textContent = '0 FCFA';
                stockDisplay.textContent = '0';
                return;
            }

            const defaultPrice = parseFloat(selectedOption.dataset.price);
            const stock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityInput.value) || 1;
            const unitPrice = parseFloat(unitPriceInput.value) || defaultPrice;
            
            // Si le champ unit_price est vide, utiliser le prix par défaut
            if (unitPriceInput.value === '' || unitPriceInput.value === '0') {
                unitPriceInput.value = defaultPrice;
            }
            
            // Mettre à jour le stock affiché
            stockDisplay.textContent = stock;
            
            // Valider la quantité
            if (quantity > stock) {
                quantityInput.classList.add('border-red-500', 'bg-red-50');
                quantityInput.setCustomValidity(`Stock insuffisant. Maximum: ${stock}`);
                totalPriceDisplay.textContent = '0 FCFA';
                totalPriceDisplay.classList.remove('bg-green-50', 'border-green-200', 'text-green-800');
                totalPriceDisplay.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
            } else {
                quantityInput.classList.remove('border-red-500', 'bg-red-50');
                quantityInput.setCustomValidity('');
                
                // Calculer le prix total
                const total = unitPrice * quantity;
                totalPriceDisplay.textContent = total.toLocaleString('fr-FR') + ' FCFA';
                totalPriceDisplay.classList.remove('bg-red-50', 'border-red-200', 'text-red-800');
                totalPriceDisplay.classList.add('bg-green-50', 'border-green-200', 'text-green-800');
            }
            
            updateSummary();
        }

        // Fonction pour mettre à jour le prix unitaire automatiquement
        function updateUnitPrice() {
            const selectedOption = productSelect.selectedOptions[0];
            
            if (selectedOption && selectedOption.value) {
                const defaultPrice = parseFloat(selectedOption.dataset.price);
                
                // Si le champ unit_price est vide ou 0, le remplir avec le prix par défaut
                if (!unitPriceInput.value || unitPriceInput.value === '0') {
                    unitPriceInput.value = defaultPrice;
                }
                
                calculateProductTotal();
            }
        }

        // Événements
        productSelect.addEventListener('change', updateUnitPrice);
        quantityInput.addEventListener('input', calculateProductTotal);
        unitPriceInput.addEventListener('input', calculateProductTotal);
        
        // Bouton supprimer
        removeBtn.addEventListener('click', function() {
            if (productRows > 1) {
                row.remove();
                productRows--;
                productCountDisplay.textContent = productRows;
                
                // Re-indexer les lignes restantes
                reindexProductRows();
                updateSummary();
            }
        });

        // Initialiser
        updateUnitPrice();
    }

    // Ré-indexer les lignes produit après suppression
    function reindexProductRows() {
        const rows = productsContainer.querySelectorAll('.product-row');
        rows.forEach((row, index) => {
            row.setAttribute('data-index', index);
            
            // Mettre à jour les noms des inputs
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price-input');
            
            productSelect.name = `products[${index}][product_id]`;
            quantityInput.name = `products[${index}][quantity]`;
            unitPriceInput.name = `products[${index}][unit_price]`;
        });
        
        productRows = rows.length;
    }

    // Initialiser toutes les lignes existantes
    function initAllProductRows() {
        productsContainer.querySelectorAll('.product-row').forEach(row => {
            initProductRow(row);
        });
    }

    // Mettre à jour le résumé général
    function updateSummary() {
        let totalQuantity = 0;
        let grandTotal = 0;
        
        productsContainer.querySelectorAll('.product-row').forEach(row => {
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price-input');
            const productSelect = row.querySelector('.product-select');
            const selectedOption = productSelect.selectedOptions[0];
            
            if (selectedOption && selectedOption.value) {
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                const stock = parseInt(selectedOption.dataset.stock);
                
                if (quantity > 0 && quantity <= stock) {
                    totalQuantity += quantity;
                    grandTotal += unitPrice * quantity;
                }
            }
        });
        
        totalQuantityDisplay.textContent = totalQuantity;
        grandTotalDisplay.textContent = grandTotal.toLocaleString('fr-FR') + ' FCFA';
    }

    // Gestion de la soumission du formulaire
    document.getElementById('saleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        let errorMessages = [];
        
        // Vérifier chaque produit
        productsContainer.querySelectorAll('.product-row').forEach((row, index) => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price-input');
            const selectedOption = productSelect.selectedOptions[0];
            
            // Vérifier si un produit est sélectionné
            if (!selectedOption || !selectedOption.value) {
                isValid = false;
                errorMessages.push(`Veuillez sélectionner un produit pour l'article ${index + 1}`);
                productSelect.focus();
                productSelect.classList.add('border-red-500', 'bg-red-50');
                return;
            }
            
            productSelect.classList.remove('border-red-500', 'bg-red-50');
            
            const productName = selectedOption.dataset.name;
            const stock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityInput.value) || 0;
            const unitPrice = parseFloat(unitPriceInput.value) || 0;
            
            // Vérifier la quantité
            if (quantity < 1) {
                isValid = false;
                errorMessages.push(`La quantité doit être au moins 1 pour "${productName}"`);
                quantityInput.focus();
                quantityInput.classList.add('border-red-500', 'bg-red-50');
                return;
            }
            
            quantityInput.classList.remove('border-red-500', 'bg-red-50');
            
            // Vérifier le prix unitaire
            if (unitPrice <= 0) {
                isValid = false;
                errorMessages.push(`Le prix unitaire doit être supérieur à 0 pour "${productName}"`);
                unitPriceInput.focus();
                unitPriceInput.classList.add('border-red-500', 'bg-red-50');
                return;
            }
            
            unitPriceInput.classList.remove('border-red-500', 'bg-red-50');
            
            // Vérifier le stock
            if (quantity > stock) {
                isValid = false;
                errorMessages.push(`Stock insuffisant pour "${productName}". Stock disponible: ${stock}`);
                quantityInput.focus();
                quantityInput.classList.add('border-red-500', 'bg-red-50');
                return;
            }
        });
        
        if (!isValid) {
            alert('Veuillez corriger les erreurs suivantes:\n\n' + errorMessages.join('\n'));
            return;
        }
        
        // Si tout est valide, soumettre le formulaire
        this.submit();
    });

    // Gestion du reset
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        // Réinitialiser à une seule ligne produit
        const firstRow = productsContainer.querySelector('.product-row:first-child');
        productsContainer.innerHTML = '';
        productsContainer.appendChild(firstRow.cloneNode(true));
        
        // Réinitialiser la première ligne
        const newFirstRow = productsContainer.querySelector('.product-row');
        newFirstRow.setAttribute('data-index', 0);
        
        // Reset les inputs de la première ligne
        newFirstRow.querySelector('.product-select').value = '';
        newFirstRow.querySelector('.quantity-input').value = '1';
        newFirstRow.querySelector('.unit-price-input').value = '0';
        
        // Reset les compteurs
        productRows = 1;
        productCountDisplay.textContent = '1';
        
        // Réinitialiser
        initAllProductRows();
        updateSummary();
    });

    // Initialiser
    initAllProductRows();
    updateSummary();
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

.quantity-input:invalid,
.product-select:invalid,
.unit-price-input:invalid {
    border-color: #ef4444;
    background-color: #fef2f2;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.product-row {
    animation: fadeIn 0.3s ease-out;
}

.remove-product:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

.total-price-display {
    min-height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}
</style>
@endsection