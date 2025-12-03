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

        <!-- Barre de Recherche -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <!-- CORRECTION : Changement de route de products.search à products.index -->
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
                        
                        <!-- Tri -->
                        <select name="sort_by" 
                                onchange="this.form.submit()" 
                                class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 border-0 focus:ring-0">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tri par date</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="stock" {{ request('sort_by') == 'stock' ? 'selected' : '' }}>Stock (croissant)</option>
                            <option value="sale_price" {{ request('sort_by') == 'sale_price' ? 'selected' : '' }}>Prix (croissant)</option>
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
                                                    'available' => 'Disponibles'
                                                ];
                                            @endphp
                                            Filtre : <strong>{{ $filterLabels[request('filter')] ?? request('filter') }}</strong> • 
                                        @endif
                                        @if(request('sort_by') != 'created_at')
                                            @php
                                                $sortLabels = [
                                                    'name' => 'Nom (A-Z)',
                                                    'stock' => 'Stock (croissant)',
                                                    'sale_price' => 'Prix (croissant)'
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total produits</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $products->total() }}</h3>
                        <p class="text-xs text-blue-100 mt-1">
                            @if(request('search') || request('filter'))
                                (Filtrés)
                            @else
                                (Tous)
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
                        <p class="text-green-100 text-sm font-medium">Stock disponible</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $totalStock }}</h3>
                        <p class="text-xs text-green-100 mt-1">
                            @if(request('search') || request('filter'))
                                (Filtrés)
                            @else
                                (Total)
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
                            <h3 class="text-3xl font-bold mt-1">{{ number_format($totalValue ?? 0, 0, ',', ' ') }}</h3>                        <p class="text-xs text-purple-100 mt-1">CFA 
                            @if(request('search') || request('filter'))
                                (Filtrés)
                            @endif
                        </p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-currency-exchange text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Date création</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-100 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm group-hover:bg-blue-100 group-hover:text-blue-700 transition-colors">
                                        {{ $product->id ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold shadow-md">
                                            {{ substr($product->name ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $product->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">Produit #{{ $product->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-bold text-gray-800">{{ isset($product->sale_price) ? number_format($product->sale_price, 0, ',', ' ') : '0' }}</span>
                                        <span class="text-xs text-gray-500">CFA</span>
                                    </div>
                                </td>
                                @if(Auth::user() && Auth::user()->role === 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-lg font-bold text-gray-800">{{ isset($product->purchase_price) ? number_format($product->purchase_price, 0, ',', ' ') : '0' }}</span>
                                            <span class="text-xs text-gray-500">CFA</span>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $stock = $product->stock ?? 0; @endphp
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold shadow-sm
                                        {{ $stock > 10 ? 'bg-green-100 text-green-800 border border-green-200' : ($stock > 0 ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-red-100 text-red-800 border border-red-200') }}">
                                        <span class="w-2 h-2 rounded-full {{ $stock > 10 ? 'bg-green-500' : ($stock > 0 ? 'bg-yellow-500' : 'bg-red-500') }} animate-pulse"></span>
                                        {{ $stock }}
                                    </span>
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
                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                               class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-800 rounded-xl shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200 group"
                                               title="Modifier">
                                                <i class="bi bi-pencil-square text-lg group-hover:scale-110 transition-transform"></i>
                                            </a>

                                            <!-- Bouton Supprimer -->
                                            <form action="{{ route('products.destroy', $product->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ?')" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200 group"
                                                        title="Supprimer">
                                                    <i class="bi bi-trash text-lg group-hover:scale-110 transition-transform"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
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

.mark {
    background-color: rgba(255, 230, 0, 0.3);
    padding: 0.1em 0.2em;
    border-radius: 0.25em;
}

.highlight {
    background-color: rgba(255, 230, 0, 0.5);
    transition: background-color 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const clearSearchBtn = document.getElementById('clearSearch');
    
    // Recherche avec délai (optionnel)
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        
        // Si recherche à la frappe activée (optionnel)
        // searchTimeout = setTimeout(() => {
        //     if (this.value.length >= 2 || this.value.length === 0) {
        //         searchForm.submit();
        //     }
        // }, 500);
    });
    
    // Empêcher l'envoi si moins de 2 caractères (optionnel)
    searchForm.addEventListener('submit', function(e) {
        const searchValue = searchInput.value.trim();
        
        // Si on veut exiger au moins 2 caractères
        // if (searchValue.length > 0 && searchValue.length < 2) {
        //     e.preventDefault();
        //     alert('Veuillez saisir au moins 2 caractères pour la recherche.');
        //     searchInput.focus();
        //     return;
        // }
        
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
    
    // Fonction pour effacer la recherche
    window.clearSearch = function() {
        window.location.href = "{{ route('products.index') }}";
    };
    
    // Touche Échap pour effacer
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchInput.value) {
            window.clearSearch();
        }
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
});
</script>
@endsection