<!-- resources/views/products/index.blade.php -->
@extends('layouts.app')

@section('title', 'Produits')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Bouton Retour -->
        <div class="mb-4">
            <button onclick="window.history.back()" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 group">
                <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
                <span>Retour</span>
            </button>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                        <span class="text-4xl">📦</span>
                        Gestion des produits
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Gérez votre inventaire et vos produits en toute simplicité</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <!-- Bouton Rapport Stocks Groupés -->
                    <a href="{{ route('reports.grouped-stocks') }}" 
                       class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-3 px-5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <i class="bi bi-layer-forward text-xl group-hover:rotate-12 transition-transform duration-300"></i>
                        <span>Stocks groupés</span>
                    </a>
                    
                    <!-- Bouton pour fusionner des produits -->
                    <button type="button" onclick="openMergeModal()"
                       class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-semibold py-3 px-5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <i class="bi bi-plus-circle-dotted text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                        <span>Fusionner</span>
                    </button>
                    
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('products.create') }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                                <i class="bi bi-plus-circle text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                                <span>Nouveau produit</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <!-- Barre de Recherche -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <form action="{{ route('products.index') }}" method="GET" id="searchForm">
                <div class="relative">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-1">
                            <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="text" 
                                name="search" 
                                id="searchInput" 
                                value="{{ request('search', '') }}" 
                                placeholder="Rechercher un produit par nom, prix ou stock..." 
                                class="w-full pl-12 pr-10 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm"
                                autocomplete="off"
                            >
                            @if(request('search'))
                                <button 
                                    type="button" 
                                    id="clearSearch" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    onclick="clearSearch()"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            @endif
                        </div>
                        <button 
                            type="submit"
                            id="searchButton"
                            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2"
                        >
                            <i class="bi bi-search"></i>
                            <span>Rechercher</span>
                        </button>
                    </div>
                    
                    <!-- Filtres rapides -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('products.index') }}" 
                           class="px-4 py-2 rounded-lg {{ !request('filter') && !request('search') ? 'bg-blue-100 text-blue-800 border border-blue-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Tous les produits
                        </a>
                        <a href="{{ route('products.index', ['filter' => 'low_stock']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'low_stock' ? 'bg-red-100 text-red-800 border border-red-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <i class="bi bi-exclamation-triangle mr-1"></i> Stock faible (≤ 10)
                        </a>
                        <a href="{{ route('products.index', ['filter' => 'out_of_stock']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'out_of_stock' ? 'bg-orange-100 text-orange-800 border border-orange-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <i class="bi bi-x-circle mr-1"></i> Rupture de stock
                        </a>
                        <a href="{{ route('products.index', ['filter' => 'available']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'available' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <i class="bi bi-check-circle mr-1"></i> Disponibles
                        </a>
                        
                        <!-- Filtre pour produits avec multiples lots -->
                        <a href="{{ route('products.index', ['filter' => 'multiple_batches']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'multiple_batches' ? 'bg-purple-100 text-purple-800 border border-purple-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <i class="bi bi-layer-forward mr-1"></i> Multiples lots
                        </a>
                        
                        <!-- Filtre pour produits cumulés -->
                        <a href="{{ route('products.index', ['filter' => 'cumulated']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'cumulated' ? 'bg-pink-100 text-pink-800 border border-pink-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <i class="bi bi-plus-circle-dotted mr-1"></i> Produits cumulés
                        </a>
                        
                        <!-- Filtre pour produits non-cumulés -->
                        <a href="{{ route('products.index', ['filter' => 'non_cumulated']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('filter') == 'non_cumulated' ? 'bg-cyan-100 text-cyan-800 border border-cyan-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <i class="bi bi-box mr-1"></i> Produits simples
                        </a>
                        
                        <!-- Tri -->
                        <select name="sort_by" 
                                onchange="this.form.submit()" 
                                class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 border-0 focus:ring-0">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tri par date</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="stock" {{ request('sort_by') == 'stock' ? 'selected' : '' }}>Stock (croissant)</option>
                            <option value="sale_price" {{ request('sort_by') == 'sale_price' ? 'selected' : '' }}>Prix (croissant)</option>
                            <option value="profit_margin" {{ request('sort_by') == 'profit_margin' ? 'selected' : '' }}>Marge %</option>
                        </select>
                        
                        <!-- Bouton Reset -->
                        @if(request('search') || request('filter') || request('sort_by') != 'created_at')
                            <a href="{{ route('products.index') }}" 
                               class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 flex items-center gap-1">
                                <i class="bi bi-x-circle"></i>
                                Réinitialiser
                            </a>
                        @endif
                    </div>
                    
                    <!-- Info de recherche -->
                    @if(request('search') || request('filter') || request('sort_by') != 'created_at')
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-info-circle text-blue-500"></i>
                                    <span class="text-sm text-blue-700">
                                        @if(request('search'))
                                            Recherche : "<strong>{{ request('search') }}</strong>" • 
                                        @endif
                                        @if(request('filter'))
                                            @php
                                                $filterLabels = [
                                                    'low_stock' => 'Stock faible (≤ 10)',
                                                    'out_of_stock' => 'Rupture de stock',
                                                    'available' => 'Disponibles',
                                                    'multiple_batches' => 'Multiples lots',
                                                    'cumulated' => 'Produits cumulés',
                                                    'non_cumulated' => 'Produits simples'
                                                ];
                                            @endphp
                                            Filtre : <strong>{{ $filterLabels[request('filter')] ?? request('filter') }}</strong> • 
                                        @endif
                                        @if(request('sort_by') != 'created_at')
                                            @php
                                                $sortLabels = [
                                                    'name' => 'Nom (A-Z)',
                                                    'stock' => 'Stock (croissant)',
                                                    'sale_price' => 'Prix (croissant)',
                                                    'profit_margin' => 'Marge %'
                                                ];
                                            @endphp
                                            Tri : <strong>{{ $sortLabels[request('sort_by')] ?? request('sort_by') }}</strong> • 
                                        @endif
                                        {{ $products->total() }} résultat(s) trouvé(s)
                                    </span>
                                </div>
                                <a href="{{ route('products.index') }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                    <i class="bi bi-x-circle"></i>
                                    Effacer
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-2xl text-green-600"></i>
                    <div>
                        <p class="font-semibold">Succès!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                <button class="absolute top-4 right-4 text-green-600 hover:text-green-800 transition-colors" onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 text-blue-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <i class="bi bi-info-circle-fill text-2xl text-blue-600"></i>
                    <div>
                        <p class="font-semibold">Information</p>
                        <p class="text-sm">{{ session('info') }}</p>
                    </div>
                </div>
                <button class="absolute top-4 right-4 text-blue-600 hover:text-blue-800 transition-colors" onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 text-yellow-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-2xl text-yellow-600"></i>
                    <div>
                        <p class="font-semibold">Attention!</p>
                        <p class="text-sm">{{ session('warning') }}</p>
                    </div>
                </div>
                <button class="absolute top-4 right-4 text-yellow-600 hover:text-yellow-800 transition-colors" onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <i class="bi bi-x-circle-fill text-2xl text-red-600"></i>
                    <div>
                        <p class="font-semibold">Erreur!</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
                <button class="absolute top-4 right-4 text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total produits</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $totalProductsGlobal }}</h3>
                        <p class="text-xs text-blue-100 mt-1">
                            @if(request('search') || request('filter'))
                                {{ $products->total() }} filtrés
                            @else
                                Tous les produits
                            @endif
                        </p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-box-seam text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Stock total</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($totalStockGlobal, 0, ',', ' ') }}</h3>
                        <p class="text-xs text-green-100 mt-1">
                            @if(request('search') || request('filter'))
                                {{ number_format($totalStockFiltered, 0, ',', ' ') }} unités filtrées
                            @else
                                Unités totales
                            @endif
                        </p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-stack text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Valeur totale</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($totalValueGlobal, 0, ',', ' ') }}</h3>
                        <p class="text-xs text-purple-100 mt-1">
                            CFA 
                            @if(request('search') || request('filter'))
                                • {{ number_format($totalValueFiltered, 0, ',', ' ') }} CFA filtrés
                            @endif
                        </p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-currency-exchange text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- CARTE : Produits avec multiples lots -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Multiples lots</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $productsWithMultipleBatches ?? 0 }}</h3>
                        <p class="text-xs text-indigo-100 mt-1">
                            Produits avec plusieurs prix
                            @if(request('filter') == 'multiple_batches')
                                <br><span class="opacity-90">Filtrés</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-layer-forward text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Bar -->
        <div class="bg-white rounded-2xl shadow-md p-4 mb-6 border border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <i class="bi bi-lightning-charge-fill text-yellow-500 text-xl"></i>
                    <span class="font-medium text-gray-700">Actions rapides :</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('reports.grouped-stocks') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 rounded-lg hover:from-indigo-100 hover:to-purple-100 border border-indigo-200 transition-colors">
                        <i class="bi bi-layer-forward"></i>
                        <span>Rapport stocks groupés</span>
                    </a>
                    <a href="{{ route('reports.products') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 rounded-lg hover:from-blue-100 hover:to-cyan-100 border border-blue-200 transition-colors">
                        <i class="bi bi-graph-up"></i>
                        <span>Rapport produits</span>
                    </a>
                    <button onclick="openMergeModal()" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 rounded-lg hover:from-purple-100 hover:to-pink-100 border border-purple-200 transition-colors">
                        <i class="bi bi-plus-circle-dotted"></i>
                        <span>Fusionner produits</span>
                    </button>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('products.create') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 rounded-lg hover:from-green-100 hover:to-emerald-100 border border-green-200 transition-colors">
                                <i class="bi bi-plus-circle"></i>
                                <span>Ajouter produit</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <!-- Products Table - Version simplifiée -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Prix de vente</th>
                            @if(Auth::user() && Auth::user()->role === 'admin')
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Prix d'achat</th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Valeur du stock</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Date création</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-100 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            @php
                                $stock = $product->stock ?? 0;
                                $salePrice = $product->sale_price ?? 0;
                                $purchasePrice = $product->purchase_price ?? 0;
                                $totalValue = $stock * $salePrice;
                                $totalCost = $stock * $purchasePrice;
                                $profitPerItem = $salePrice - $purchasePrice;
                                $totalProfit = $profitPerItem * $stock;
                                
                                // Classes pour le stock
                                $stockClass = $stock > 10 ? 'success' : ($stock > 0 ? 'warning' : 'danger');
                                
                                // Vérifier si le produit a plusieurs lots
                                $hasMultipleBatches = $product->has_multiple_batches ?? false;
                                $batchesCount = $product->stock_summary['number_of_batches'] ?? 1;
                                
                                // Vérifier le statut cumulé
                                $isCumulated = $product->is_cumulated ?? false;
                                $hasBeenCumulated = $product->has_been_cumulated ?? false;
                                $cumulatedTo = $product->cumulated_to ?? null;
                                
                                // Déterminer le badge de statut
                                $statusBadge = 'simple';
                                $statusText = 'Produit simple';
                                $statusColor = 'gray';
                                
                                if ($isCumulated) {
                                    $statusBadge = 'cumulated';
                                    $statusText = 'Produit cumulé';
                                    $statusColor = 'purple';
                                } elseif ($hasBeenCumulated) {
                                    $statusBadge = 'merged';
                                    $statusText = 'Fusionné vers #' . ($cumulatedTo ?? '?');
                                    $statusColor = 'pink';
                                } elseif ($hasMultipleBatches) {
                                    $statusBadge = 'multiple';
                                    $statusText = $batchesCount . ' lot(s)';
                                    $statusColor = 'indigo';
                                }
                            @endphp
                            <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group
                                @if($hasBeenCumulated) opacity-75 hover:opacity-100 @endif">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                        @if($isCumulated) bg-purple-100 text-purple-700 @elseif($hasBeenCumulated) bg-pink-100 text-pink-700 @else bg-gray-100 text-gray-700 @endif
                                        font-semibold text-sm group-hover:bg-blue-100 group-hover:text-blue-700 transition-colors">
                                        {{ $product->id ?? 'N/A' }}
                                        @if($hasBeenCumulated)
                                            <i class="bi bi-arrow-right-short ml-0.5 text-xs"></i>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg 
                                            @if($isCumulated) bg-gradient-to-br from-purple-400 to-pink-600 @elseif($hasBeenCumulated) bg-gradient-to-br from-pink-400 to-rose-600 @else bg-gradient-to-br from-blue-400 to-blue-600 @endif
                                            flex items-center justify-center text-white font-bold shadow-md">
                                            {{ substr($product->name ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 flex items-center gap-2">
                                                {{ $product->name ?? 'N/A' }}
                                                @if($isCumulated)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs">
                                                        <i class="bi bi-plus-circle-dotted"></i>
                                                        Cumulé
                                                    </span>
                                                @endif
                                                @if($hasBeenCumulated)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-pink-100 text-pink-700 rounded-full text-xs">
                                                        <i class="bi bi-arrow-right"></i>
                                                        Fusionné
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">#{{ $product->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-bold text-gray-800">{{ number_format($salePrice, 0, ',', ' ') }}</span>
                                        <span class="text-xs text-gray-500">CFA</span>
                                        @if($isCumulated && $product->stock_summary['average_sale_price'] ?? false)
                                            <div class="text-xs text-purple-600 mt-1">
                                                <i class="bi bi-graph-up"></i>
                                                Moyenne: {{ number_format($product->stock_summary['average_sale_price'], 0, ',', ' ') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                @if(Auth::user() && Auth::user()->role === 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-lg font-bold text-gray-800">{{ number_format($purchasePrice, 0, ',', ' ') }}</span>
                                            <span class="text-xs text-gray-500">CFA</span>
                                            @if($isCumulated && $product->stock_summary['average_purchase_price'] ?? false)
                                                <div class="text-xs text-purple-600 mt-1">
                                                    <i class="bi bi-graph-down"></i>
                                                    Moyenne: {{ number_format($product->stock_summary['average_purchase_price'], 0, ',', ' ') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold shadow-sm
                                            {{ $stockClass === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                            ($stockClass === 'warning' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-red-100 text-red-800 border border-red-200') }}">
                                            <span class="w-2 h-2 rounded-full 
                                                {{ $stockClass === 'success' ? 'bg-green-500' : 
                                                ($stockClass === 'warning' ? 'bg-yellow-500' : 'bg-red-500') }} animate-pulse"></span>
                                            {{ $stock }}
                                        </span>
                                        @if($isCumulated && $product->stock_summary['total_quantity_all_batches'] ?? false)
                                            <div class="text-xs text-gray-500 pl-1">
                                                <i class="bi bi-stack"></i>
                                                Cumul: {{ $product->stock_summary['total_quantity_all_batches'] }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <!-- Valeur totale du stock -->
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg font-bold text-purple-700">
                                                {{ number_format($totalValue, 0, ',', ' ') }}
                                            </span>
                                            <span class="text-xs text-gray-500">CFA</span>
                                        </div>
                                        
                                        <!-- Info détaillée (optionnel) -->
                                        @if(Auth::user() && Auth::user()->role === 'admin' && $purchasePrice > 0)
                                            <div class="text-xs text-gray-500 mt-1">
                                                <div class="flex items-center gap-1">
                                                    <span class="text-green-600">+{{ number_format($totalProfit, 0, ',', ' ') }} CFA</span>
                                                    <span class="text-gray-400">•</span>
                                                    <span>Bénéfice</span>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($isCumulated && $product->stock_summary['total_value_current'] ?? false)
                                            <div class="text-xs text-purple-600 mt-1">
                                                <i class="bi bi-calculator"></i>
                                                Valeur totale: {{ number_format($product->stock_summary['total_value_current'], 0, ',', ' ') }} CFA
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($statusBadge == 'cumulated')
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 rounded-full border border-purple-200 text-sm font-medium">
                                                <i class="bi bi-plus-circle-dotted"></i>
                                                {{ $statusText }}
                                            </span>
                                            @if($product->batch_number ?? false)
                                                <div class="text-xs text-gray-500 pl-1">
                                                    <i class="bi bi-tag"></i>
                                                    {{ $product->batch_number }}
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($statusBadge == 'merged')
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-pink-100 to-rose-100 text-pink-700 rounded-full border border-pink-200 text-sm font-medium">
                                                <i class="bi bi-arrow-right"></i>
                                                {{ $statusText }}
                                            </span>
                                            <div class="text-xs text-pink-600 pl-1">
                                                <i class="bi bi-info-circle"></i>
                                                Non modifiable
                                            </div>
                                        </div>
                                    @elseif($statusBadge == 'multiple')
                                        <div class="flex items-center gap-2 group/lot relative">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full border border-indigo-200 text-sm font-medium hover:from-indigo-200 hover:to-purple-200 transition-colors cursor-pointer"
                                                  onclick="window.location.href='{{ route('reports.grouped-stocks') }}?search={{ urlencode($product->name) }}'">
                                                <i class="bi bi-layer-forward"></i>
                                                {{ $statusText }}
                                            </span>
                                            <!-- Tooltip -->
                                            <div class="absolute left-0 bottom-full mb-2 hidden group-hover/lot:block z-10">
                                                <div class="bg-gray-800 text-white text-xs rounded-lg py-2 px-3 shadow-lg">
                                                    <div class="font-semibold mb-1">{{ $product->name }}</div>
                                                    <div class="text-gray-300">
                                                        {{ $batchesCount }} lot(s) différents<br>
                                                        <span class="text-indigo-300">Cliquez pour voir les détails</span>
                                                    </div>
                                                    <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-800 rotate-45"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-600 rounded-full border border-gray-200 text-sm">
                                            <i class="bi bi-box"></i>
                                            {{ $statusText }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="bi bi-calendar3 text-gray-400"></i>
                                        {{ $product->created_at?->format('d/m/Y') ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ $product->created_at?->format('H:i') ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-3">
                                        <!-- Bouton Voir -->
                                        <a href="{{ route('products.show', $product->id) }}" 
                                        class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200 group"
                                        title="Voir détails">
                                            <i class="bi bi-eye text-lg group-hover:scale-110 transition-transform"></i>
                                        </a>

                                        @if(Auth::user() && Auth::user()->role === 'admin')
                                            @if(!$hasBeenCumulated)
                                                <!-- Bouton Modifier -->
                                                <a href="{{ route('products.edit', $product->id) }}" 
                                                class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-800 rounded-xl shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200 group"
                                                title="Modifier">
                                                    <i class="bi bi-pencil-square text-lg group-hover:scale-110 transition-transform"></i>
                                                </a>
                                            @endif

                                            <!-- Bouton Supprimer -->
                                            <form action="{{ route('products.destroy', $product->id) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ?')" 
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200 group"
                                                        title="Supprimer"
                                                        @if($hasBeenCumulated || ($isCumulated && $originalCount > 0)) disabled @endif>
                                                    <i class="bi bi-trash text-lg group-hover:scale-110 transition-transform"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Bouton pour dé-cumuler -->
                                            @if($isCumulated)
                                                <form action="{{ route('products.uncumulate', $product->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir défaire ce cumul ? Les produits originaux seront restaurés.')"
                                                      class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200 group"
                                                            title="Défaire le cumul">
                                                        <i class="bi bi-arrow-counterclockwise text-lg group-hover:scale-110 transition-transform"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user() && Auth::user()->role === 'admin' ? 9 : 8 }}" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-box-seam text-5xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">
                                            @if(request('search') || request('filter'))
                                                Aucun produit ne correspond à vos critères
                                            @else
                                                Aucun produit trouvé
                                            @endif
                                        </h3>
                                        <p class="text-gray-500 mb-6">
                                            @if(request('search') || request('filter'))
                                                Essayez de modifier vos critères de recherche
                                            @else
                                                Commencez par créer votre premier produit
                                            @endif
                                        </p>
                                        @if(request('search') || request('filter'))
                                            <a href="{{ route('products.index') }}" 
                                            class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-5 py-2.5 rounded-xl font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                <span>Voir tous les produits</span>
                                            </a>
                                        @else
                                            <a href="{{ route('products.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                                <i class="bi bi-plus-circle"></i>
                                                <span>Créer le premier produit</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="mt-6 bg-white rounded-xl shadow-md p-4">
            @if($products->hasPages())
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Page {{ $products->currentPage() }} sur {{ $products->lastPage() }} • 
                        {{ $products->total() }} produit(s) au total
                        @if(request('search'))
                            • Recherche : "{{ request('search') }}"
                        @endif
                    </div>
                    <div class="pagination">
                        {{ $products->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 py-2">
                    @if($products->count() > 0)
                        Tous les produits sont affichés
                    @else
                        Aucun produit à afficher
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- MODAL POUR FUSIONNER DES PRODUITS -->
<div id="mergeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <!-- En-tête fixe -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white flex-shrink-0">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold flex items-center gap-3">
                    <i class="bi bi-plus-circle-dotted text-3xl"></i>
                    Fusionner des produits
                </h3>
                <button type="button" onclick="closeMergeModal()" class="text-white hover:text-gray-200 text-2xl">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <p class="text-purple-100 mt-2">Sélectionnez les produits à fusionner en un seul produit cumulé</p>
        </div>
        
        <!-- Conteneur scrollable -->
        <div class="flex-1 overflow-y-auto">
            <form action="{{ route('products.merge') }}" method="POST" id="mergeForm" class="p-6 space-y-6">
                @csrf
                
                <!-- Sélection des produits -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-boxes mr-1"></i>
                        Produits à fusionner <span class="text-red-500">*</span>
                    </label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="productsSelection">
                            @foreach($products as $product)
                                @if(!($product->has_been_cumulated ?? false))
                                    <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                                        <input type="checkbox" 
                                               name="product_ids[]" 
                                               value="{{ $product->id }}" 
                                               id="product_{{ $product->id }}"
                                               onchange="updateMergeSelection()"
                                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <label for="product_{{ $product->id }}" class="flex-1 cursor-pointer">
                                            <div class="font-medium text-gray-800">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500 flex items-center gap-2">
                                                <span>Stock: {{ $product->stock }}</span>
                                                <span>•</span>
                                                <span>{{ number_format($product->sale_price, 0, ',', ' ') }} CFA</span>
                                            </div>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if(!$products->where('has_been_cumulated', false)->count())
                            <div class="text-center py-8 text-gray-500">
                                <i class="bi bi-exclamation-triangle text-3xl mb-3"></i>
                                <p>Aucun produit disponible pour fusion</p>
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Sélectionnez au moins 2 produits non-cumulés</p>
                    <div id="selectionCount" class="mt-2 text-sm text-purple-600 hidden">
                        <i class="bi bi-check-circle"></i>
                        <span id="selectedCount">0</span> produit(s) sélectionné(s)
                    </div>
                </div>
                
                <!-- Détails du nouveau produit -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-600"></i>
                        Informations du produit fusionné
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nom du produit fusionné <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="mergeName"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Ex: Produit Fusionné"
                                   required>
                        </div>
                        
                        <!-- CATÉGORIE -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    id="mergeCategorySelect"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    required>
                                <option value="">Chargement...</option>
                            </select>
                        </div>
                        
                        <!-- FOURNISSEUR -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Fournisseur <span class="text-red-500">*</span>
                            </label>
                            <select name="supplier_id" 
                                    id="mergeSupplierSelect"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    required>
                                <option value="">Chargement...</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Référence du lot
                            </label>
                            <input type="text" 
                                   name="batch_reference" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Ex: MERGE-2024"
                                   value="MERGE-{{ date('Ymd-His') }}">
                        </div>
                    </div>
                </div>
                
                <!-- Aperçu du cumul -->
                <div id="mergePreview" class="hidden">
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <i class="bi bi-eye text-green-600"></i>
                        Aperçu de la fusion
                    </h4>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Stock total:</span>
                                <span id="previewTotalStock" class="font-bold text-green-700 ml-2">0</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Valeur totale:</span>
                                <span id="previewTotalValue" class="font-bold text-green-700 ml-2">0 CFA</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Produits à fusionner:</span>
                                <span id="previewProductCount" class="font-bold text-green-700 ml-2">0</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Prix moyen:</span>
                                <span id="previewAvgPrice" class="font-bold text-green-700 ml-2">0 CFA</span>
                            </div>
                        </div>
                        <div class="mt-3 text-xs text-green-600">
                            <i class="bi bi-info-circle"></i>
                            Les produits sélectionnés seront marqués comme "fusionnés" et ne pourront plus être modifiés.
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Actions du modal - Pied fixe -->
        <div class="border-t border-gray-200 bg-gray-50 p-6 flex-shrink-0">
            <div class="flex items-center justify-end gap-3">
                <button type="button" 
                        onclick="closeMergeModal()"
                        class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors">
                    Annuler
                </button>
                <button type="submit" 
                        form="mergeForm"
                        id="mergeSubmitBtn"
                        disabled
                        class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <i class="bi bi-plus-circle-dotted mr-2"></i>
                    Fusionner les produits
                </button>
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

@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

.mark {
    background-color: rgba(255, 230, 0, 0.3);
    padding: 0.1em 0.2em;
    border-radius: 0.25em;
}

.highlight {
    background-color: rgba(255, 230, 0, 0.5);
    transition: background-color 0.3s ease;
}

/* Style pour les tooltips */
.group\/lot .absolute {
    min-width: 180px;
    white-space: nowrap;
}

/* Modal styles */
#mergeModal {
    display: none;
}

#mergeModal.show {
    display: flex;
    animation: fade-in 0.2s ease-out;
}

#mergeModal > div {
    animation: slide-in 0.3s ease-out;
}

/* Style pour produits sélectionnés dans le modal */
input[name="product_ids[]"]:checked + label > div {
    @apply font-bold text-purple-700;
}

input[name="product_ids[]"]:checked + label > div:first-child {
    @apply underline decoration-purple-300;
}

/* Scrollbar styling */
#mergeModal .overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #c7d2fe #f3f4f6;
}

#mergeModal .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

#mergeModal .overflow-y-auto::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

#mergeModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c7d2fe;
    border-radius: 3px;
}

#mergeModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a5b4fc;
}
</style>

<script>
// Données des produits pour la fusion
let productsData = {};

// Initialiser les données des produits
function initializeMergeData() {
    console.log('📊 Initialisation des données des produits...');
    productsData = {};
    
    @foreach($products as $product)
        @if(!($product->has_been_cumulated ?? false))
            productsData[{{ $product->id }}] = {
                name: '{{ addslashes($product->name) }}',
                stock: {{ $product->stock }},
                salePrice: {{ $product->sale_price }},
                purchasePrice: {{ $product->purchase_price }},
                categoryId: {{ $product->category_id ?? 0 }},
                supplierId: {{ $product->supplier_id ?? 0 }}
            };
        @endif
    @endforeach
    
    console.log(`✅ ${Object.keys(productsData).length} produits disponibles pour fusion`);
}

// Fonction pour charger les catégories et fournisseurs
async function loadModalData() {
    console.log('🔍 Chargement des données pour le modal...');
    
    const categorySelect = document.getElementById('mergeCategorySelect');
    const supplierSelect = document.getElementById('mergeSupplierSelect');
    
    // Mettre en état de chargement
    categorySelect.innerHTML = '<option value="">Chargement...</option>';
    supplierSelect.innerHTML = '<option value="">Chargement...</option>';
    
    try {
        // Charger les catégories
        console.log('📦 Chargement des catégories...');
        const categoriesResponse = await fetch('{{ route("api.modal.categories") }}');
        console.log('📦 Réponse catégories:', categoriesResponse.status);
        const categoriesData = await categoriesResponse.json();
        console.log('📦 Données catégories:', categoriesData);
        
        if (categoriesData.success && categoriesData.data && categoriesData.data.length > 0) {
            categorySelect.innerHTML = '<option value="">Sélectionner une catégorie</option>';
            categoriesData.data.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
            console.log(`✅ ${categoriesData.data.length} catégories chargées`);
        } else {
            categorySelect.innerHTML = '<option value="">Aucune catégorie disponible</option>';
            console.warn('⚠️ Aucune catégorie disponible');
        }
    } catch (error) {
        console.error('❌ Erreur chargement catégories:', error);
        categorySelect.innerHTML = '<option value="">Erreur de chargement</option>';
    }
    
    try {
        // Charger les fournisseurs
        console.log('🏭 Chargement des fournisseurs...');
        const suppliersResponse = await fetch('{{ route("api.modal.suppliers") }}');
        console.log('🏭 Réponse fournisseurs:', suppliersResponse.status);
        const suppliersData = await suppliersResponse.json();
        console.log('🏭 Données fournisseurs:', suppliersData);
        
        if (suppliersData.success && suppliersData.data && suppliersData.data.length > 0) {
            supplierSelect.innerHTML = '<option value="">Sélectionner un fournisseur</option>';
            suppliersData.data.forEach(supplier => {
                const option = document.createElement('option');
                option.value = supplier.id;
                option.textContent = supplier.name;
                supplierSelect.appendChild(option);
            });
            console.log(`✅ ${suppliersData.data.length} fournisseurs chargés`);
        } else {
            supplierSelect.innerHTML = '<option value="">Aucun fournisseur disponible</option>';
            console.warn('⚠️ Aucun fournisseur disponible');
        }
    } catch (error) {
        console.error('❌ Erreur chargement fournisseurs:', error);
        supplierSelect.innerHTML = '<option value="">Erreur de chargement</option>';
    }
    
    // Après chargement, pré-sélectionner la catégorie et le fournisseur du premier produit sélectionné
    setTimeout(preselectFirstProduct, 100);
}

// Pré-sélectionner la catégorie et le fournisseur du premier produit sélectionné
function preselectFirstProduct() {
    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
    if (checkboxes.length > 0) {
        const firstProductId = checkboxes[0].value;
        const product = productsData[firstProductId];
        
        if (product) {
            console.log('🎯 Pré-sélection pour le produit:', product.name);
            
            // Pré-sélectionner la catégorie
            const categorySelect = document.getElementById('mergeCategorySelect');
            if (categorySelect && product.categoryId > 0) {
                console.log('🎯 Recherche catégorie ID:', product.categoryId);
                for (let i = 0; i < categorySelect.options.length; i++) {
                    if (categorySelect.options[i].value == product.categoryId) {
                        categorySelect.value = product.categoryId;
                        console.log('✅ Catégorie pré-sélectionnée:', categorySelect.options[i].text);
                        break;
                    }
                }
            }
            
            // Pré-sélectionner le fournisseur
            const supplierSelect = document.getElementById('mergeSupplierSelect');
            if (supplierSelect && product.supplierId > 0) {
                console.log('🎯 Recherche fournisseur ID:', product.supplierId);
                for (let i = 0; i < supplierSelect.options.length; i++) {
                    if (supplierSelect.options[i].value == product.supplierId) {
                        supplierSelect.value = product.supplierId;
                        console.log('✅ Fournisseur pré-sélectionné:', supplierSelect.options[i].text);
                        break;
                    }
                }
            }
        }
    }
}

// Ouvrir le modal
function openMergeModal() {
    console.log('🚀 Ouverture du modal de fusion');
    
    // Ouvrir le modal
    const modal = document.getElementById('mergeModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Réinitialiser le formulaire
    document.getElementById('mergeForm').reset();
    
    // Réinitialiser les sélections
    document.querySelectorAll('input[name="product_ids[]"]').forEach(cb => cb.checked = false);
    
    // Initialiser les données
    initializeMergeData();
    
    // Charger les catégories et fournisseurs
    loadModalData();
    
    // Mettre à jour la sélection
    updateMergeSelection();
}

// Fermer le modal
function closeMergeModal() {
    console.log('❌ Fermeture du modal');
    document.getElementById('mergeModal').classList.remove('show');
    document.body.style.overflow = '';
}

// Mettre à jour la sélection des produits
function updateMergeSelection() {
    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
    const count = checkboxes.length;
    const countElement = document.getElementById('selectedCount');
    const previewElement = document.getElementById('mergePreview');
    const submitBtn = document.getElementById('mergeSubmitBtn');
    const selectionCountElement = document.getElementById('selectionCount');
    
    // Mettre à jour le compteur
    if (countElement) {
        countElement.textContent = count;
    }
    
    // Afficher/masquer l'aperçu
    if (previewElement) {
        if (count >= 2) {
            previewElement.classList.remove('hidden');
            updateMergePreview(checkboxes);
        } else {
            previewElement.classList.add('hidden');
        }
    }
    
    // Activer/désactiver le bouton
    if (submitBtn) {
        submitBtn.disabled = count < 2;
    }
    
    // Afficher/masquer le compteur
    if (selectionCountElement) {
        if (count > 0) {
            selectionCountElement.classList.remove('hidden');
        } else {
            selectionCountElement.classList.add('hidden');
        }
    }
    
    // Générer un nom par défaut
    if (count >= 1) {
        const firstProductId = checkboxes[0].value;
        const firstProduct = productsData[firstProductId];
        if (firstProduct && !document.getElementById('mergeName').value) {
            document.getElementById('mergeName').value = firstProduct.name + ' (Fusionné)';
        }
    }
    
    // Pré-sélectionner catégorie/fournisseur du premier produit
    if (count > 0) {
        preselectFirstProduct();
    }
}

// Mettre à jour l'aperçu
function updateMergePreview(checkboxes) {
    let totalStock = 0;
    let totalValue = 0;
    let totalSalePrice = 0;
    let productCount = 0;
    
    checkboxes.forEach(cb => {
        const productId = cb.value;
        const product = productsData[productId];
        if (product) {
            totalStock += product.stock;
            totalValue += product.stock * product.salePrice;
            totalSalePrice += product.salePrice;
            productCount++;
        }
    });
    
    const avgSalePrice = productCount > 0 ? Math.round(totalSalePrice / productCount) : 0;
    
    // Mettre à jour l'aperçu
    const previewTotalStock = document.getElementById('previewTotalStock');
    const previewTotalValue = document.getElementById('previewTotalValue');
    const previewProductCount = document.getElementById('previewProductCount');
    const previewAvgPrice = document.getElementById('previewAvgPrice');
    
    if (previewTotalStock) previewTotalStock.textContent = totalStock;
    if (previewTotalValue) previewTotalValue.textContent = totalValue.toLocaleString('fr-FR') + ' CFA';
    if (previewProductCount) previewProductCount.textContent = productCount;
    if (previewAvgPrice) previewAvgPrice.textContent = avgSalePrice.toLocaleString('fr-FR') + ' CFA';
}

// Fonction pour effacer la recherche
function clearSearch() {
    window.location.href = "{{ route('products.index') }}";
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Page produits chargée');
    initializeMergeData();
    
    // Recherche avec délai
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
    });
    
    // Empêcher l'envoi si moins de 2 caractères
    searchForm.addEventListener('submit', function(e) {
        const searchValue = searchInput.value.trim();
        
        // Ajouter un indicateur de chargement
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Recherche...';
        submitBtn.disabled = true;
        
        // Réinitialiser après 2 secondes (sécurité)
        setTimeout(() => {
            submitBtn.innerHTML = originalHtml;
            submitBtn.disabled = false;
        }, 2000);
    });
    
    // Mettre en évidence les résultats de recherche
    const searchTerm = "{{ request('search', '') }}";
    if (searchTerm) {
        highlightSearchResults(searchTerm);
    }
    
    function highlightSearchResults(term) {
        const elements = document.querySelectorAll('td:not(:last-child)');
        const regex = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        
        elements.forEach(element => {
            const originalHtml = element.innerHTML;
            const highlighted = originalHtml.replace(regex, '<mark class="bg-yellow-200 text-gray-800 px-1 rounded">$1</mark>');
            element.innerHTML = highlighted;
        });
    }
    
    // Ajouter un bouton de test debug
    const debugBtn = document.createElement('button');
    debugBtn.innerHTML = '🐛 Debug API';
    debugBtn.className = 'fixed bottom-20 right-4 bg-gray-800 text-white px-3 py-2 rounded-lg text-xs z-50 opacity-50 hover:opacity-100 transition-opacity';
    debugBtn.onclick = async function() {
        console.log('=== DEBUG API ===');
        console.log('API Categories URL:', '{{ route("api.modal.categories") }}');
        console.log('API Suppliers URL:', '{{ route("api.modal.suppliers") }}');
        
        // Test direct des APIs
        try {
            const catRes = await fetch('{{ route("api.modal.categories") }}');
            const catData = await catRes.json();
            console.log('Catégories API:', catData);
        } catch (e) {
            console.error('Erreur catégories:', e);
        }
        
        try {
            const supRes = await fetch('{{ route("api.modal.suppliers") }}');
            const supData = await supRes.json();
            console.log('Fournisseurs API:', supData);
        } catch (e) {
            console.error('Erreur fournisseurs:', e);
        }
    };
    document.body.appendChild(debugBtn);
});

// Validation du formulaire
document.getElementById('mergeForm')?.addEventListener('submit', function(e) {
    const checkboxes = this.querySelectorAll('input[name="product_ids[]"]:checked');
    if (checkboxes.length < 2) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins 2 produits à fusionner.');
        return;
    }
    
    const categorySelect = document.getElementById('mergeCategorySelect');
    const supplierSelect = document.getElementById('mergeSupplierSelect');
    
    if (!categorySelect.value) {
        e.preventDefault();
        alert('Veuillez sélectionner une catégorie.');
        categorySelect.focus();
        return;
    }
    
    if (!supplierSelect.value) {
        e.preventDefault();
        alert('Veuillez sélectionner un fournisseur.');
        supplierSelect.focus();
        return;
    }
    
    // Afficher un indicateur de chargement
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalHtml = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i> Fusion en cours...';
    submitBtn.disabled = true;
    
    // Réinitialiser après 10 secondes (sécurité)
    setTimeout(() => {
        submitBtn.innerHTML = originalHtml;
        submitBtn.disabled = false;
    }, 10000);
});
</script>
@endsection