@extends('layouts.app')

@section('title', 'Détails de la catégorie : ' . $category->name)

@section('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #3b82f6;
    }
    
    .stock-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .stock-ok {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .stock-low {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .stock-empty {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
    }
    
    .category-header {
        border-radius: 16px;
        color: white;
    }
    
    .product-image-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .subcategory-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .subcategory-indicator {
        margin-left: 24px;
        border-left: 2px solid #e2e8f0;
        padding-left: 16px;
    }

    .hierarchy-path {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .hierarchy-path a {
        color: #3b82f6;
        text-decoration: none;
    }

    .hierarchy-path a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Bouton Retour -->
        <div class="mb-4">
            <a href="{{ route('categories.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2 group">                
                <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
                <span>Retour aux catégories</span>
            </a>
        </div>

        <!-- Chemin hiérarchique -->
        @if($category->parent || $category->children->count() > 0)
        <div class="mb-6 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline">Catégories</a>
                <i class="bi bi-chevron-right text-gray-400"></i>
                
                @if($category->parent)
                    <a href="{{ route('categories.show', $category->parent->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                        {{ $category->parent->name }}
                    </a>
                    <i class="bi bi-chevron-right text-gray-400"></i>
                @endif
                
                <span class="font-semibold text-gray-800">{{ $category->name }}</span>
                
                @if($category->children->count() > 0)
                    <span class="ml-2 text-gray-500 text-xs bg-gray-100 px-2 py-1 rounded">
                        +{{ $category->children->count() }} sous-catégorie(s)
                    </span>
                @endif
            </div>
        </div>
        @endif

        <!-- Header Catégorie -->
        <div class="category-header p-8 mb-8 shadow-xl" style="background: {{ $category->color ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="{{ $category->icon ?? 'bi-folder' }} text-5xl text-white"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-4xl font-bold text-white">{{ $category->name }}</h1>
                            @if($category->parent)
                                <span class="subcategory-badge bg-white/30 text-white">
                                    <i class="bi bi-folder-symlink"></i>
                                    Sous-catégorie
                                </span>
                            @else
                                <span class="subcategory-badge bg-white/30 text-white">
                                    <i class="bi bi-folder-fill"></i>
                                    Catégorie principale
                                </span>
                            @endif
                        </div>
                        
                        @if($category->parent)
                            <p class="text-white/80 text-lg mb-3">
                                <i class="bi bi-arrow-up-right"></i> 
                                Sous-catégorie de : 
                                <a href="{{ route('categories.show', $category->parent->id) }}" class="text-white font-semibold hover:underline">
                                    {{ $category->parent->name }}
                                </a>
                            </p>
                        @endif
                        
                        @if($category->description)
                            <p class="text-white/90 max-w-2xl">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="flex gap-3">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('categories.edit', $category->id) }}" 
                           class="bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 backdrop-blur-sm">
                            <i class="bi bi-pencil-square"></i>
                            <span>Modifier</span>
                        </a>
                        
                        <form action="{{ route('categories.destroy', $category->id) }}" 
                              method="POST" 
                              onsubmit="return confirmDelete();"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500/80 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                                <i class="bi bi-trash"></i>
                                <span>Supprimer</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <!-- Informations de base -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm">
                    <p class="text-white/70 text-sm">ID Catégorie</p>
                    <p class="text-white text-xl font-bold">#{{ $category->id }}</p>
                </div>
                
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm">
                    <p class="text-white/70 text-sm">Date création</p>
                    <p class="text-white text-xl font-bold">{{ $category->created_at->format('d/m/Y') }}</p>
                </div>
                
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm">
                    <p class="text-white/70 text-sm">Dernière mise à jour</p>
                    <p class="text-white text-xl font-bold">{{ $category->updated_at->format('d/m/Y') }}</p>
                </div>
                
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm">
                    <p class="text-white/70 text-sm">Statut</p>
                    <p class="text-white text-xl font-bold">
                        @if($stats['total_products'] > 0)
                            <span class="inline-flex items-center gap-1">
                                <i class="bi bi-check-circle"></i>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                Vide
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Produits</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_products'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                        <i class="bi bi-box-seam text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Sous-catégories</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_subcategories'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-purple-100 text-purple-600 rounded-full p-3">
                        <i class="bi bi-folder-symlink text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Valeur Stock</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_value'] ?? 0, 0, ',', ' ') }} CFA</h3>
                    </div>
                    <div class="bg-green-100 text-green-600 rounded-full p-3">
                        <i class="bi bi-currency-exchange text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Revenu Potentiel</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($stats['potential_revenue'] ?? 0, 0, ',', ' ') }} CFA</h3>
                    </div>
                    <div class="bg-yellow-100 text-yellow-600 rounded-full p-3">
                        <i class="bi bi-graph-up text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes Stock -->
        @if($stats['out_of_stock'] > 0 || $stats['low_stock'] > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @if($stats['out_of_stock'] > 0)
            <div class="bg-gradient-to-br from-red-50 to-red-100 border-l-4 border-red-500 p-6 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="bg-red-500 text-white rounded-full p-3">
                        <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-800">Produits en Rupture</h4>
                        <p class="text-red-700 text-3xl font-bold mt-1">{{ $stats['out_of_stock'] ?? 0 }}</p>
                        <p class="text-red-600 text-sm mt-1">Produits à réapprovisionner</p>
                    </div>
                </div>
            </div>
            @endif
            
            @if($stats['low_stock'] > 0)
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border-l-4 border-yellow-500 p-6 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-500 text-white rounded-full p-3">
                        <i class="bi bi-exclamation-circle-fill text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-yellow-800">Stock Faible</h4>
                        <p class="text-yellow-700 text-3xl font-bold mt-1">{{ $stats['low_stock'] ?? 0 }}</p>
                        <p class="text-yellow-600 text-sm mt-1">Produits avec stock ≤ 5</p>
                    </div>
                </div>
            </div>
            @endif
            
            @if($stats['in_stock'] > 0)
            <div class="bg-gradient-to-br from-green-50 to-green-100 border-l-4 border-green-500 p-6 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="bg-green-500 text-white rounded-full p-3">
                        <i class="bi bi-check-circle-fill text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-green-800">Stock Suffisant</h4>
                        <p class="text-green-700 text-3xl font-bold mt-1">{{ $stats['in_stock'] ?? 0 }}</p>
                        <p class="text-green-600 text-sm mt-1">Produits avec stock > 5</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Sous-catégories (si catégorie principale) -->
        @if($category->children->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="bi bi-folder-symlink text-xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Sous-catégories</h2>
                            <p class="text-purple-100 text-sm">{{ $category->children->count() }} sous-catégorie(s)</p>
                        </div>
                    </div>
                    <a href="{{ route('categories.create') }}?parent_id={{ $category->id }}" 
                       class="bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        <span>Ajouter</span>
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child->id) }}" class="block">
                            <div class="product-card p-5 hover:border-purple-300">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="product-image-placeholder" style="background: {{ $child->color ?? 'linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%)' }}; color: white;">
                                            <i class="{{ $child->icon ?? 'bi-folder-symlink' }}"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ $child->name }}</h4>
                                            <p class="text-gray-500 text-sm">ID: #{{ $child->id }}</p>
                                        </div>
                                    </div>
                                    
                                    <span class="stock-badge bg-purple-100 text-purple-800">
                                        {{ $child->products_count ?? 0 }} produits
                                    </span>
                                </div>
                                
                                @if($child->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($child->description, 80) }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center gap-1">
                                        <i class="bi bi-eye"></i>
                                        Voir détails
                                    </span>
                                    
                                    <div class="text-xs text-gray-500">
                                        <i class="bi bi-calendar"></i>
                                        {{ $child->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Liste des Produits -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8">
            <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="bi bi-box text-xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Produits de cette catégorie</h2>
                            <p class="text-gray-200 text-sm">{{ $category->products->count() }} produit(s)</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('products.create') }}?category_id={{ $category->id }}" 
                           class="bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 transition-all">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajouter</span>
                        </a>
                        <a href="{{ route('products.index') }}?category_id={{ $category->id }}" 
                           class="bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 transition-all">
                            <i class="bi bi-arrow-right"></i>
                            <span>Voir tous</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if($category->products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($category->products as $product)
                            <div class="product-card p-5">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="product-image-placeholder bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                                            {{ substr($product->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ $product->name }}</h4>
                                            <p class="text-gray-500 text-sm">ID: #{{ $product->id }}</p>
                                        </div>
                                    </div>
                                    
                                    <span class="stock-badge {{ $product->stock > 10 ? 'stock-ok' : ($product->stock > 0 ? 'stock-low' : 'stock-empty') }}">
                                        {{ $product->stock }} unités
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <p class="text-gray-500 text-xs">Prix Achat</p>
                                        <p class="font-semibold text-gray-700">{{ number_format($product->purchase_price, 0, ',', ' ') }} CFA</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs">Prix Vente</p>
                                        <p class="font-semibold text-green-600">{{ number_format($product->sale_price, 0, ',', ' ') }} CFA</p>
                                    </div>
                                </div>
                                
                                @if($product->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('products.show', $product->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                                        <i class="bi bi-eye"></i>
                                        Voir détails
                                    </a>
                                    
                                    <div class="text-xs text-gray-500">
                                        <i class="bi bi-calendar"></i>
                                        {{ $product->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-box text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun produit dans cette catégorie</h3>
                        <p class="text-gray-500 mb-6">Cette catégorie ne contient pas encore de produits.</p>
                        <a href="{{ route('products.create') }}?category_id={{ $category->id }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajouter un produit</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tableau des produits à faible stock -->
        @if($lowStockProducts && $lowStockProducts->count() > 0)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="bi bi-exclamation-triangle text-xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Produits à faible stock</h2>
                            <p class="text-red-100 text-sm">Produits nécessitant une attention immédiate</p>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-red-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-red-800 uppercase">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-red-800 uppercase">Stock Actuel</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-red-800 uppercase">Seuil d'alerte</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-red-800 uppercase">Prix Vente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-red-800 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($lowStockProducts as $product)
                                <tr class="hover:bg-red-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white font-bold">
                                                {{ substr($product->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-gray-500 text-sm">ID: #{{ $product->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $product->stock == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $product->stock }} unités
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        @if($product->stock == 0)
                                            <span class="font-semibold">RUPTURE</span>
                                        @else
                                            <span class="font-semibold">{{ $product->stock }}/5</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-semibold text-green-600">{{ number_format($product->sale_price, 0, ',', ' ') }} CFA</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                                <i class="bi bi-box-arrow-in-up"></i>
                                                Réapprovisionner
                                            </a>
                                            <a href="{{ route('products.show', $product->id) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                                                <i class="bi bi-eye"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Informations techniques -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mt-8">
            <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="bi bi-info-circle text-xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Informations techniques</h2>
                        <p class="text-gray-200 text-sm">Détails système de la catégorie</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">Métadonnées</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">ID de la catégorie</span>
                                <span class="font-medium">#{{ $category->id }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Type</span>
                                <span class="font-medium">
                                    @if($category->parent_id)
                                        <span class="text-purple-600">Sous-catégorie</span>
                                    @else
                                        <span class="text-green-600">Catégorie principale</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Date de création</span>
                                <span class="font-medium">{{ $category->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Dernière modification</span>
                                <span class="font-medium">{{ $category->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Produits associés</span>
                                <span class="font-medium">{{ $category->products->count() }}</span>
                            </div>
                            @if($category->parent)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">Catégorie parente</span>
                                    <span class="font-medium text-blue-600">
                                        <a href="{{ route('categories.show', $category->parent->id) }}" class="hover:underline">
                                            {{ $category->parent->name }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                            @if($category->children->count() > 0)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">Sous-catégories</span>
                                    <span class="font-medium">{{ $category->children->count() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">Historique</h3>
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i class="bi bi-plus"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm">Création de la catégorie</p>
                                    <p class="text-gray-500 text-xs">{{ $category->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            @if($category->created_at != $category->updated_at)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                        <i class="bi bi-pencil"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm">Dernière modification</p>
                                        <p class="text-gray-500 text-xs">{{ $category->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    const hasProducts = {{ $category->products->count() }} > 0;
    const hasSubcategories = {{ $category->children->count() }} > 0;
    
    let message = "Êtes-vous sûr de vouloir supprimer cette catégorie ?\n\n";
    
    if (hasProducts) {
        message += "⚠️ Cette catégorie contient {{ $category->products->count() }} produit(s).\n";
    }
    
    if (hasSubcategories) {
        message += "⚠️ Cette catégorie contient {{ $category->children->count() }} sous-catégorie(s).\n";
    }
    
    if (hasProducts || hasSubcategories) {
        message += "\nSi vous la supprimez :\n";
        if (hasProducts) message += "- Les produits seront sans catégorie\n";
        if (hasSubcategories) message += "- Les sous-catégories deviendront des catégories principales\n";
        message += "\nVoulez-vous vraiment continuer ?";
    } else {
        message += "Voulez-vous vraiment continuer ?";
    }
    
    return confirm(message);
}
</script>
@endsection