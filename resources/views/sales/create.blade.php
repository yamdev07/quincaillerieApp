@extends('layouts.app')

@section('title', 'Nouvelle vente')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                        <span class="text-4xl">🛒</span>
                        Nouvelle vente
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Enregistrez une nouvelle transaction de vente</p>
                </div>
                <a href="{{ route('sales.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                    <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-300"></i>
                    <span>Retour aux ventes</span>
                </a>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Client & Summary -->
                <div class="space-y-6">
                    <!-- Client Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="bi bi-person-badge text-blue-500"></i>
                            Informations client
                        </h3>
                        <div class="form-group">
                            <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-2">Sélectionnez un client</label>
                            <select name="client_id" id="client_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all duration-200 text-gray-700">
                                <option value="">-- Client inconnu --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <i class="bi bi-receipt"></i>
                            Récapitulatif
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-blue-100">Articles :</span>
                                <span class="font-semibold" id="items-count">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-100">Quantité totale :</span>
                                <span class="font-semibold" id="total-quantity">0</span>
                            </div>
                            <div class="border-t border-blue-400 pt-3 mt-3">
                                <div class="flex justify-between items-center text-lg">
                                    <span class="font-bold">Total :</span>
                                    <span class="font-bold text-2xl" id="grand-total">0 FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <i class="bi bi-check-circle text-xl group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="text-lg">Enregistrer la vente</span>
                    </button>
                </div>

                <!-- Products Table -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                    <i class="bi bi-cart-plus text-purple-500"></i>
                                    Articles de la vente
                                </h3>
                                <button type="button" id="add-product" class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                                    <i class="bi bi-plus-circle group-hover:rotate-90 transition-transform duration-300"></i>
                                    <span>Ajouter</span>
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full" id="products-table">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-gray-800 to-gray-700 text-white">
                                            <th class="px-4 py-3 text-left font-semibold rounded-l-xl">Produit</th>
                                            <th class="px-4 py-3 text-center font-semibold">Prix unitaire</th>
                                            <th class="px-4 py-3 text-center font-semibold">Quantité</th>
                                            <th class="px-4 py-3 text-center font-semibold">Total</th>
                                            <th class="px-4 py-3 text-center font-semibold rounded-r-xl">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr class="product-row hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4">
                                                <select name="products[0][product_id]" class="product-select w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-50 transition-all duration-200" required>
                                                    <option value="">-- Choisir un produit --</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                                            {{ $product->name }} (Stock: {{ $product->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="unit-price font-semibold text-gray-600">0 FCFA</span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <input type="number" name="products[0][quantity]" class="quantity-input w-20 border-2 border-gray-200 rounded-lg px-3 py-2 text-center focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-50 transition-all duration-200" min="1" value="1" required>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="line-total font-bold text-gray-800">0 FCFA</span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <button type="button" class="remove-product bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white w-8 h-8 rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200 flex items-center justify-center">
                                                    <i class="bi bi-dash-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Empty State -->
                            <div id="empty-state" class="hidden text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-cart-x text-4xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-600 mb-2">Aucun article</h4>
                                <p class="text-gray-500 text-sm">Ajoutez des produits à votre vente</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-lightbulb text-2xl text-blue-500 mt-1"></i>
                            <div>
                                <h3 class="font-semibold text-blue-800 mb-2">Conseils de vente</h3>
                                <ul class="text-blue-700 text-sm space-y-1">
                                    <li>• Vérifiez les stocks disponibles avant de valider</li>
                                    <li>• Sélectionnez le client si connu pour le suivi</li>
                                    <li>• Les prix sont en Francs CFA (FCFA)</li>
                                    <li>• Vous pouvez ajouter plusieurs articles</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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

/* Style pour les selects et inputs */
.product-select:focus, .quantity-input:focus {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}

/* Style pour les lignes de produit */
.product-row {
    transition: all 0.3s ease;
}

.product-row:hover {
    background-color: #f8fafc;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;

    function updateLineTotal(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const unitPriceTd = row.querySelector('.unit-price');
        const lineTotalTd = row.querySelector('.line-total');

        const price = parseFloat(select.selectedOptions[0]?.dataset.price || 0);
        const qty = parseInt(quantityInput.value) || 0;
        const total = price * qty;

        unitPriceTd.textContent = price.toLocaleString('fr-FR') + ' FCFA';
        lineTotalTd.textContent = total.toLocaleString('fr-FR') + ' FCFA';

        updateSummary();
        checkStockAvailability(row);
    }

    function checkStockAvailability(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const stock = parseInt(select.selectedOptions[0]?.dataset.stock || 0);
        const qty = parseInt(quantityInput.value) || 0;

        if (select.value && qty > stock) {
            quantityInput.classList.add('border-red-500', 'bg-red-50');
            quantityInput.title = `Stock insuffisant! Disponible: ${stock}`;
        } else {
            quantityInput.classList.remove('border-red-500', 'bg-red-50');
            quantityInput.title = '';
        }
    }

    function updateSummary() {
        let grandTotal = 0;
        let totalQuantity = 0;
        const items = document.querySelectorAll('.product-row');
        
        items.forEach(row => {
            const totalText = row.querySelector('.line-total').textContent;
            const total = parseFloat(totalText.replace(/\s|FCFA/g, '')) || 0;
            const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
            
            grandTotal += total;
            totalQuantity += qty;
        });

        document.getElementById('grand-total').textContent = grandTotal.toLocaleString('fr-FR') + ' FCFA';
        document.getElementById('total-quantity').textContent = totalQuantity;
        document.getElementById('items-count').textContent = items.length;

        // Show/hide empty state
        const emptyState = document.getElementById('empty-state');
        const tableBody = document.querySelector('#products-table tbody');
        if (items.length === 0) {
            emptyState.classList.remove('hidden');
            tableBody.classList.add('hidden');
        } else {
            emptyState.classList.add('hidden');
            tableBody.classList.remove('hidden');
        }
    }

    // Event delegation for dynamic elements
    document.getElementById('products-table').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            updateLineTotal(e.target.closest('.product-row'));
        }
    });

    document.getElementById('products-table').addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            updateLineTotal(e.target.closest('.product-row'));
        }
    });

    document.getElementById('add-product').addEventListener('click', function() {
        const tbody = document.querySelector('#products-table tbody');
        const newRow = tbody.querySelector('tr').cloneNode(true);

        // Reset values
        newRow.querySelectorAll('select, input').forEach(el => {
            if (el.tagName === 'SELECT') {
                el.value = '';
            } else if (el.classList.contains('quantity-input')) {
                el.value = 1;
            }
        });
        newRow.querySelector('.unit-price').textContent = '0 FCFA';
        newRow.querySelector('.line-total').textContent = '0 FCFA';

        // Update input names
        newRow.querySelectorAll('select').forEach(select => {
            select.name = `products[${rowIndex}][product_id]`;
        });
        newRow.querySelectorAll('input').forEach(input => {
            input.name = `products[${rowIndex}][quantity]`;
        });

        tbody.appendChild(newRow);
        rowIndex++;
        updateSummary();
    });

    document.getElementById('products-table').addEventListener('click', function(e) {
        if (e.target.closest('.remove-product')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('.product-row').length > 1) {
                row.remove();
                updateSummary();
            } else {
                // If it's the last row, just clear it
                row.querySelector('select').value = '';
                row.querySelector('input').value = 1;
                updateLineTotal(row);
            }
        }
    });

    // Form validation
    document.getElementById('sale-form').addEventListener('submit', function(e) {
        let hasErrors = false;
        const productRows = document.querySelectorAll('.product-row');
        
        productRows.forEach(row => {
            const select = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const stock = parseInt(select.selectedOptions[0]?.dataset.stock || 0);
            const qty = parseInt(quantityInput.value) || 0;

            if (!select.value) {
                select.classList.add('border-red-500', 'bg-red-50');
                hasErrors = true;
            } else {
                select.classList.remove('border-red-500', 'bg-red-50');
            }

            if (qty > stock) {
                quantityInput.classList.add('border-red-500', 'bg-red-50');
                hasErrors = true;
            }
        });

        if (hasErrors) {
            e.preventDefault();
            alert('⚠️ Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
        }
    });

    // Initial calculation
    document.querySelectorAll('.product-row').forEach(updateLineTotal);
    updateSummary();
});
</script>
@endsection