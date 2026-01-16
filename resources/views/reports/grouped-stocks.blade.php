{{-- resources/views/reports/grouped-stocks.blade.php --}}
@extends('layouts.app')

@section('title', 'Rapport des stocks groupés par lot')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                        <i class="fas fa-boxes text-4xl text-blue-500"></i>
                        Stocks groupés par lot/prix
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Analyse détaillée des stocks par lot de prix d'achat</p>
                </div>
                <div>
                    <a href="{{ route('reports.grouped-stocks.export', ['format' => 'csv']) }}" 
                       class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <i class="fas fa-file-csv text-lg group-hover:rotate-12 transition-transform duration-300"></i>
                        <span>Exporter CSV</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <form method="GET" action="{{ route('reports.grouped-stocks') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                        <select name="category_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fournisseur</label>
                        <select name="supplier_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Tous les fournisseurs</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                        <select name="sort_by" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nom produit</option>
                            <option value="total_value" {{ request('sort_by') == 'total_value' ? 'selected' : '' }}>Valeur totale</option>
                            <option value="batches_count" {{ request('sort_by') == 'batches_count' ? 'selected' : '' }}>Nombre de lots</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-filter"></i>
                            <span>Filtrer</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Produits -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Produits</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $reportStats['total_products'] }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Valeur totale -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Valeur totale</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($reportStats['total_value'], 0, ',', ' ') }} F</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Total lots -->
            <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 text-sm font-medium">Total lots</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $reportStats['total_batches'] }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-layer-group text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Produits multi-lots -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Produits multi-lots</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $reportStats['products_with_multiple_batches'] }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-clone text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau principal -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- En-tête du tableau -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <h2 class="text-xl font-bold text-gray-800">Détail des stocks par produit</h2>
                <div class="flex gap-2">
                    <a href="{{ route('products.index') }}" 
                       class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
            
            <!-- Tableau -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock total</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Prix vente</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Prix achat moy.</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Valeur totale</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Lots</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Marge</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($productsData as $index => $data)
                            @php
                                $product = $data['product'];
                                $summary = $data['summary'];
                                $marge = $summary['current_price'] - $summary['average_purchase_price'];
                                $marge_pourcentage = $summary['average_purchase_price'] > 0 
                                    ? ($marge / $summary['average_purchase_price']) * 100 
                                    : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold shadow-md">
                                            {{ substr($product->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</div>
                                        </div>
                                        @if($summary['has_multiple_batches'])
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                <i class="fas fa-layer-group"></i>
                                                Multiple
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-semibold text-sm">
                                        {{ number_format($summary['total_stock'], 0) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-semibold text-gray-900">
                                    {{ number_format($summary['current_price'], 0, ',', ' ') }} F
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-semibold text-gray-900">
                                    {{ number_format($summary['average_purchase_price'], 0, ',', ' ') }} F
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-bold text-green-600">
                                    {{ number_format($summary['total_value'], 0, ',', ' ') }} F
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-800 font-semibold text-sm">
                                        {{ $summary['batches_count'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold {{ $marge_pourcentage >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <span class="w-2 h-2 rounded-full {{ $marge_pourcentage >= 0 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ number_format($marge_pourcentage, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button type="button" 
                                            onclick="toggleDetails('details-{{ $product->id }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition-colors font-medium">
                                        <i class="fas fa-chevron-down"></i>
                                        Détails
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Détails des lots (hidden by default) -->
                            <tr id="details-{{ $product->id }}" class="hidden">
                                <td colspan="9" class="p-0">
                                    <div class="p-6 bg-gray-50 border-t border-gray-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                                <i class="fas fa-boxes text-blue-500"></i>
                                                Détail des lots - {{ $product->name }}
                                            </h3>
                                            <button onclick="toggleDetails('details-{{ $product->id }}')"
                                                    class="text-gray-500 hover:text-gray-700">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-sm">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Lot/Référence</th>
                                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Quantité</th>
                                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Prix d'achat</th>
                                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Valeur achat</th>
                                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Valeur actuelle</th>
                                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Bénéfice</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    @foreach($data['grouped_stocks'] as $batch)
                                                        @php
                                                            $valeur_achat = $batch->total_quantity * $batch->purchase_price;
                                                            $valeur_actuelle = $batch->total_quantity * $summary['current_price'];
                                                            $benefice = $valeur_actuelle - $valeur_achat;
                                                        @endphp
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-4 py-3">
                                                                <span class="px-2 py-1 text-xs font-medium bg-gray-800 text-white rounded">
                                                                    {{ $batch->batch ?? 'Lot ' . $loop->iteration }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-center font-medium">{{ number_format($batch->total_quantity, 0) }}</td>
                                                            <td class="px-4 py-3 text-right font-medium">{{ number_format($batch->purchase_price, 0, ',', ' ') }} F</td>
                                                            <td class="px-4 py-3 text-right font-medium">{{ number_format($valeur_achat, 0, ',', ' ') }} F</td>
                                                            <td class="px-4 py-3 text-right font-bold text-blue-600">{{ number_format($valeur_actuelle, 0, ',', ' ') }} F</td>
                                                            <td class="px-4 py-3 text-right font-bold {{ $benefice >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                {{ $benefice >= 0 ? '+' : '' }}{{ number_format($benefice, 0, ',', ' ') }} F
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                    <!-- Ligne TOTALE -->
                                                    <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 font-bold border-t-2 border-blue-200">
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center gap-2">
                                                                <i class="fas fa-calculator text-blue-600"></i>
                                                                <span class="text-blue-700">TOTAL {{ $product->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-center text-blue-700">{{ number_format($summary['total_stock'], 0) }}</td>
                                                        <td class="px-4 py-3 text-right">
                                                            <div>
                                                                <div class="text-xs text-blue-600">Moyenne</div>
                                                                {{ number_format($summary['average_purchase_price'], 0, ',', ' ') }} F
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-right text-blue-700">
                                                            {{ number_format($data['totals']['total_value_purchase'] ?? 0, 0, ',', ' ') }} F
                                                        </td>
                                                        <td class="px-4 py-3 text-right text-green-700">
                                                            {{ number_format($summary['total_value'], 0, ',', ' ') }} F
                                                        </td>
                                                        <td class="px-4 py-3 text-right text-green-700">
                                                            +{{ number_format($data['totals']['profit_potential'] ?? 0, 0, ',', ' ') }} F
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if(count($productsData) === 0)
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-box-open text-4xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun produit trouvé</h3>
                                        <p class="text-gray-500">Aucun produit ne correspond à vos critères de filtrage.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Note -->
        <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <div>
                    <p class="font-medium text-blue-800">Comment lire ce rapport ?</p>
                    <p class="text-sm text-blue-600 mt-1">
                        Ce rapport montre les produits qui ont été achetés à différents prix (lots). 
                        Chaque ligne représente un produit, avec une <strong>ligne TOTALE</strong> en bas qui regroupe 
                        tous les lots de ce produit. Cliquez sur "Détails" pour voir le détail par lot.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animation pour le toggle des détails */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>

<script>
    // Fonction pour afficher/masquer les détails
    function toggleDetails(id) {
        const element = document.getElementById(id);
        if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
            element.classList.add('animate-fadeIn');
        } else {
            element.classList.add('hidden');
        }
    }
    
    // Fermer les autres détails quand on ouvre un
    document.addEventListener('DOMContentLoaded', function() {
        const detailButtons = document.querySelectorAll('button[onclick^="toggleDetails"]');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                
                // Fermer tous les autres détails ouverts
                detailButtons.forEach(otherButton => {
                    if (otherButton !== button) {
                        const otherTargetId = otherButton.getAttribute('onclick').match(/'([^']+)'/)[1];
                        const otherElement = document.getElementById(otherTargetId);
                        if (otherElement && !otherElement.classList.contains('hidden')) {
                            otherElement.classList.add('hidden');
                        }
                    }
                });
            });
        });
    });
</script>
@endsection