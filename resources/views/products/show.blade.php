@extends('layouts.app')

@section('title', 'Produit : ' . $product->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            <i class="fas fa-box mr-2"></i>{{ $product->name }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            Détails complets du produit #{{ $product->id }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        @if(Auth::user() && Auth::user()->role === 'admin')
                            <a href="{{ route('products.edit', $product->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition">
                                <i class="fas fa-edit mr-2"></i> Modifier
                            </a>
                        @endif
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Stock Card -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm text-blue-800 dark:text-blue-300">Stock disponible</div>
                                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $product->stock ?? 0 }}</div>
                                <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">unités</div>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                <i class="fas fa-boxes text-blue-600 dark:text-blue-300"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            @php 
                                $stock = $product->stock ?? 0;
                                $stockStatus = $stock > 10 ? 'excellent' : ($stock > 0 ? 'attention' : 'critique');
                                $statusClasses = [
                                    'excellent' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'attention' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'critique' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ];
                            @endphp
                            <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full {{ $statusClasses[$stockStatus] }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $stockStatus === 'excellent' ? 'bg-green-500' : ($stockStatus === 'attention' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                {{ $stockStatus === 'excellent' ? 'Stock excellent' : ($stockStatus === 'attention' ? 'Stock faible' : 'Rupture de stock') }}
                            </span>
                        </div>
                    </div>

                    <!-- Sale Price Card -->
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm text-green-800 dark:text-green-300">Prix de vente</div>
                                <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                                    {{ number_format($product->sale_price ?? 0, 0, ',', ' ') }}
                                </div>
                                <div class="text-xs text-green-600 dark:text-green-400 mt-1">CFA/unité</div>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-800 flex items-center justify-center">
                                <i class="fas fa-tag text-green-600 dark:text-green-300"></i>
                            </div>
                        </div>
                        @if(Auth::user() && Auth::user()->role === 'admin')
                            <div class="mt-3">
                                <div class="text-xs text-gray-600 dark:text-gray-400">Prix d'achat moyen</div>
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ number_format(isset($stockSummary['average_purchase_price']) ? $stockSummary['average_purchase_price'] : $product->purchase_price, 0, ',', ' ') }} CFA
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Total Value Card -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm text-purple-800 dark:text-purple-300">Valeur totale</div>
                                <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                                    @php
                                        $totalValue = isset($stockSummary['total_value']) ? $stockSummary['total_value'] : ($product->stock * $product->sale_price);
                                    @endphp
                                    {{ number_format($totalValue, 0, ',', ' ') }}
                                </div>
                                <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">CFA</div>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-800 flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-purple-600 dark:text-purple-300"></i>
                            </div>
                        </div>
                        @if(Auth::user() && Auth::user()->role === 'admin' && isset($stockSummary['profit_potential']))
                            <div class="mt-3">
                                <div class="text-xs text-gray-600 dark:text-gray-400">Bénéfice potentiel</div>
                                <div class="text-sm font-medium text-green-600 dark:text-green-400">
                                    +{{ number_format($stockSummary['profit_potential'], 0, ',', ' ') }} CFA
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Batches Card -->
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg border border-indigo-200 dark:border-indigo-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm text-indigo-800 dark:text-indigo-300">Lots/Stocks</div>
                                <div class="text-2xl font-bold text-indigo-900 dark:text-indigo-100">
                                    {{ isset($stockSummary['batches_count']) ? $stockSummary['batches_count'] : 1 }}
                                </div>
                                <div class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                                    @if(isset($stockSummary['has_multiple_batches']) && $stockSummary['has_multiple_batches'])
                                        Multiples lots
                                    @else
                                        Lot unique
                                    @endif
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center">
                                <i class="fas fa-layer-group text-indigo-600 dark:text-indigo-300"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('reports.grouped-stocks') }}?search={{ urlencode($product->name) }}" 
                               class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline">
                                <i class="fas fa-arrow-right mr-1"></i>
                                Voir le détail des lots
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Product Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Product Details Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    Informations détaillées
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Basic Info -->
                                    <div class="space-y-4">
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Nom du produit</div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Catégorie</div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $product->category->name ?? 'Non catégorisé' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Fournisseur</div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $product->supplier->name ?? 'Non spécifié' }}</div>
                                        </div>
                                    </div>

                                    <!-- Financial Info -->
                                    <div class="space-y-4">
                                        @if(Auth::user() && Auth::user()->role === 'admin')
                                            <div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Prix d'achat actuel</div>
                                                <div class="font-semibold text-gray-900 dark:text-white">
                                                    {{ number_format($product->purchase_price ?? 0, 0, ',', ' ') }} CFA
                                                </div>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Prix de vente</div>
                                            <div class="font-semibold text-green-600 dark:text-green-400">
                                                {{ number_format($product->sale_price ?? 0, 0, ',', ' ') }} CFA
                                            </div>
                                        </div>
                                        @if($product->purchase_price > 0)
                                            <div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Marge unitaire</div>
                                                <div class="font-semibold text-green-600 dark:text-green-400">
                                                    +{{ number_format($product->sale_price - $product->purchase_price, 0, ',', ' ') }} CFA
                                                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                                        ({{ number_format(($product->sale_price - $product->purchase_price) / $product->purchase_price * 100, 1, ',', ' ') }}%)
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">
                                        <i class="fas fa-align-left text-orange-500 mr-2"></i>
                                        Description
                                    </h4>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                        @if($product->description)
                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $product->description }}</p>
                                        @else
                                            <div class="text-center text-gray-400 dark:text-gray-500 py-4">
                                                <i class="fas fa-align-left text-3xl mb-2"></i>
                                                <p class="text-sm">Aucune description</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stocks Groupés par Lot -->
                        @if(isset($stockSummary['has_multiple_batches']) && $stockSummary['has_multiple_batches'])
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            <i class="fas fa-layer-group text-indigo-500 mr-2"></i>
                                            Stocks par lot/prix
                                        </h3>
                                        <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-medium">
                                            {{ $stockSummary['batches_count'] }} lot(s)
                                        </span>
                                    </div>

                                    @if(isset($stockByPrice) && count($stockByPrice) > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-900">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lot/Référence</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prix d'achat</th>
                                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valeur achat</th>
                                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valeur actuelle</th>
                                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bénéfice</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($stockByPrice as $batch)
                                                        @php
                                                            $valeur_achat = ($batch->total_quantity ?? 0) * ($batch->purchase_price ?? 0);
                                                            $valeur_actuelle = ($batch->total_quantity ?? 0) * ($product->sale_price ?? 0);
                                                            $benefice = $valeur_actuelle - $valeur_achat;
                                                        @endphp
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                                            <td class="px-4 py-3 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                                                    <span class="font-medium text-gray-900 dark:text-white">
                                                                        {{ $batch->reference_document ?? 'Lot ' . $loop->iteration }}
                                                                    </span>
                                                                </div>
                                                                @if(isset($batch->last_update) && $batch->last_update)
                                                                    <div class="text-xs text-gray-500 dark:text-gray-400 ml-4">
                                                                        {{ $batch->last_update->format('d/m/Y') }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="font-semibold text-gray-900 dark:text-white">
                                                                    {{ number_format($batch->total_quantity ?? 0, 0) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                                                    {{ number_format($batch->purchase_price ?? 0, 0, ',', ' ') }} CFA
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                                                    {{ number_format($valeur_achat, 0, ',', ' ') }} CFA
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <span class="font-semibold text-blue-600 dark:text-blue-400">
                                                                    {{ number_format($valeur_actuelle, 0, ',', ' ') }} CFA
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <span class="font-semibold {{ $benefice >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                                    {{ $benefice >= 0 ? '+' : '' }}{{ number_format($benefice, 0, ',', ' ') }} CFA
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    <!-- Ligne TOTAL -->
                                                    @php
                                                        $totalValuePurchase = isset($stockTotals['total_value_purchase']) ? $stockTotals['total_value_purchase'] : 0;
                                                        $profitPotential = isset($stockTotals['profit_potential']) ? $stockTotals['profit_potential'] : 0;
                                                    @endphp
                                                    <tr class="bg-indigo-50 dark:bg-indigo-900/30 font-bold">
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-calculator text-indigo-600 dark:text-indigo-400 mr-2"></i>
                                                                <span class="text-indigo-700 dark:text-indigo-300">TOTAL {{ $product->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-center">
                                                            <span class="text-indigo-700 dark:text-indigo-300">{{ number_format($stockSummary['total_stock'] ?? 0, 0) }}</span>
                                                        </td>
                                                        <td class="px-4 py-3 text-right">
                                                            <div class="text-indigo-700 dark:text-indigo-300">
                                                                <div class="text-xs text-indigo-600 dark:text-indigo-400">Moyenne</div>
                                                                {{ number_format($stockSummary['average_purchase_price'] ?? 0, 0, ',', ' ') }} CFA
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-right">
                                                            <span class="text-indigo-700 dark:text-indigo-300">
                                                                {{ number_format($totalValuePurchase, 0, ',', ' ') }} CFA
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3 text-right">
                                                            <span class="text-green-700 dark:text-green-400">
                                                                {{ number_format($stockSummary['total_value'] ?? 0, 0, ',', ' ') }} CFA
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3 text-right">
                                                            <span class="text-green-700 dark:text-green-400">
                                                                +{{ number_format($profitPotential, 0, ',', ' ') }} CFA
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Summary Stats -->
                                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded">
                                                    <div class="text-sm text-blue-600 dark:text-blue-400">Prix d'achat moyen</div>
                                                    <div class="text-lg font-bold text-blue-700 dark:text-blue-300">
                                                        {{ number_format($stockSummary['average_purchase_price'] ?? 0, 0, ',', ' ') }} CFA
                                                    </div>
                                                </div>
                                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded">
                                                    <div class="text-sm text-green-600 dark:text-green-400">Valeur totale actuelle</div>
                                                    <div class="text-lg font-bold text-green-700 dark:text-green-300">
                                                        {{ number_format($stockSummary['total_value'] ?? 0, 0, ',', ' ') }} CFA
                                                    </div>
                                                </div>
                                                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded">
                                                    <div class="text-sm text-purple-600 dark:text-purple-400">Bénéfice potentiel total</div>
                                                    <div class="text-lg font-bold text-purple-700 dark:text-purple-300">
                                                        +{{ number_format($profitPotential, 0, ',', ' ') }} CFA
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 flex justify-center">
                                            <a href="{{ route('reports.grouped-stocks') }}" 
                                               class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md transition">
                                                <i class="fas fa-chart-bar mr-2"></i>
                                                <span>Voir le rapport complet des stocks groupés</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-layer-group text-3xl text-gray-400 dark:text-gray-600"></i>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Aucun lot disponible</h4>
                                            <p class="text-gray-500 dark:text-gray-400">Les stocks groupés par lot apparaîtront ici.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Actions & Info -->
                    <div class="space-y-6">
                        <!-- Quick Actions -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">
                                    <i class="fas fa-bolt text-purple-500 mr-2"></i>
                                    Actions rapides
                                </h3>
                                
                                @if(Auth::user() && Auth::user()->role === 'admin')
                                    <div class="space-y-3">
                                        <!-- Restock Button -->
                                        <button onclick="openRestockModal()" 
                                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition">
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            <span>Réapprovisionner</span>
                                        </button>

                                        <!-- Stock Adjustment -->
                                        <button onclick="openAdjustmentModal()" 
                                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                            <i class="fas fa-sliders-h mr-2"></i>
                                            <span>Ajuster le stock</span>
                                        </button>

                                        <!-- Quick Sale -->
                                        <button onclick="openQuickSaleModal()" 
                                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-md transition">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            <span>Vente rapide</span>
                                        </button>

                                        <!-- Edit Product -->
                                        <a href="{{ route('products.edit', $product->id) }}" 
                                           class="w-full inline-flex justify-center items-center px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md transition">
                                            <i class="fas fa-edit mr-2"></i>
                                            <span>Modifier le produit</span>
                                        </a>

                                        <!-- History -->
                                        <a href="{{ route('products.history', $product->id) }}" 
                                           class="w-full inline-flex justify-center items-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition">
                                            <i class="fas fa-history mr-2"></i>
                                            <span>Voir l'historique</span>
                                        </a>

                                        <!-- Delete Product -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition">
                                                <i class="fas fa-trash mr-2"></i>
                                                <span>Supprimer le produit</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Meta Info -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    Métadonnées
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">ID du produit</div>
                                        <div class="font-mono font-bold text-gray-900 dark:text-white">{{ $product->id }}</div>
                                    </div>
                                    
                                    <div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Créé le</div>
                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $product->created_at?->format('d/m/Y à H:i') ?? 'N/A' }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Dernière modification</div>
                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $product->updated_at?->format('d/m/Y à H:i') ?? 'N/A' }}
                                        </div>
                                    </div>

                                    @if($product->category)
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Catégorie</div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $product->category->name }}</div>
                                        </div>
                                    @endif

                                    @if($product->supplier)
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Fournisseur</div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $product->supplier->name }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stock Consistency Check -->
                        @if(isset($stockConsistency) && $stockConsistency['is_consistent'] === false)
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg shadow border border-red-200 dark:border-red-800">
                                <div class="p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900 flex items-center justify-center">
                                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-red-800 dark:text-red-400">Incohérence détectée</h3>
                                            <p class="text-sm text-red-600 dark:text-red-300">Vérifiez les mouvements de stock</p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Stock actuel :</span>
                                            <span class="font-semibold">{{ $stockConsistency['current_stock'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Stock calculé :</span>
                                            <span class="font-semibold">{{ $stockConsistency['calculated_stock'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Différence :</span>
                                            <span class="font-semibold {{ $stockConsistency['difference'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $stockConsistency['difference'] >= 0 ? '+' : '' }}{{ $stockConsistency['difference'] }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('products.history', $product->id) }}" 
                                           class="inline-flex items-center gap-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:underline">
                                            <i class="fas fa-search"></i>
                                            <span>Vérifier l'historique</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Stock Movements -->
                <div class="mt-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    <i class="fas fa-history text-blue-500 mr-2"></i>
                                    Derniers mouvements
                                </h3>
                                <a href="{{ route('products.history', $product->id) }}" 
                                   class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline">
                                    <span>Voir tout</span>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>

                            @php
                                $recentMovements = $product->stockMovements()->limit(5)->get();
                            @endphp
                            
                            @if($recentMovements->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-900">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Motif</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock après</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($recentMovements as $movement)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white">
                                                            {{ $movement->created_at->format('d/m/Y') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $movement->created_at->format('H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        @if($movement->type == 'entree')
                                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border border-green-200 dark:border-green-800">
                                                                <i class="fas fa-arrow-down"></i>
                                                                Entrée
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-800">
                                                                <i class="fas fa-arrow-up"></i>
                                                                Sortie
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <span class="font-semibold {{ $movement->type == 'entree' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                            {{ $movement->type == 'entree' ? '+' : '-' }}{{ $movement->quantity }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $movement->motif }}</span>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $movement->stock_after }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-clock text-3xl text-gray-400 dark:text-gray-600"></i>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Aucun mouvement récent</h4>
                                    <p class="text-gray-500 dark:text-gray-400">Les mouvements de stock apparaîtront ici.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FontAwesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
function openRestockModal() {
    alert('Fonctionnalité de réapprovisionnement à implémenter');
}

function openAdjustmentModal() {
    alert('Fonctionnalité d\'ajustement de stock à implémenter');
}

function openQuickSaleModal() {
    alert('Fonctionnalité de vente rapide à implémenter');
}
</script>
@endsection