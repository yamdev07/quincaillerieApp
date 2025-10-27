@extends('layouts.app')

@section('title', 'Produit : ' . $product->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        {{ substr($product->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            {{ $product->name }}
                        </h2>
                        <p class="text-gray-500 mt-1 text-sm">Détails complets du produit #{{ $product->id }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('products.edit', $product->id) }}" class="bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-800 font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <i class="bi bi-pencil-square text-lg group-hover:rotate-12 transition-transform duration-300"></i>
                        <span>Modifier</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <i class="bi bi-arrow-left-circle text-lg group-hover:-translate-x-1 transition-transform duration-300"></i>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                            <i class="bi bi-info-circle text-blue-500"></i>
                            Informations du produit
                        </h3>
                        
                        <div class="space-y-6">
                            <!-- Price -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                                        <i class="bi bi-currency-dollar text-2xl text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Prix unitaire</p>
                                        <p class="text-2xl font-bold text-gray-800">
                                            {{ isset($product->price) ? number_format($product->price, 0, ',', ' ') : '0' }}
                                            <span class="text-sm text-gray-500">CFA</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Valeur totale</p>
                                    <p class="text-lg font-semibold text-gray-800">
                                        {{ number_format(($product->price ?? 0) * ($product->stock ?? 0), 0, ',', ' ') }}
                                        <span class="text-sm text-gray-500">CFA</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                        <i class="bi bi-box-seam text-2xl text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Stock actuel</p>
                                        <p class="text-2xl font-bold text-gray-800">{{ $product->stock ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    @php 
                                        $stock = $product->stock ?? 0;
                                        $stockStatus = $stock > 10 ? 'excellent' : ($stock > 0 ? 'attention' : 'critique');
                                        $statusColors = [
                                            'excellent' => ['bg' => 'bg-green-500', 'text' => 'text-green-800', 'bg-light' => 'bg-green-100'],
                                            'attention' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-800', 'bg-light' => 'bg-yellow-100'],
                                            'critique' => ['bg' => 'bg-red-500', 'text' => 'text-red-800', 'bg-light' => 'bg-red-100']
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$stockStatus]['text'] }} {{ $statusColors[$stockStatus]['bg-light'] }} border border-opacity-20">
                                        <span class="w-3 h-3 rounded-full {{ $statusColors[$stockStatus]['bg'] }} animate-pulse"></span>
                                        {{ $stockStatus === 'excellent' ? 'Stock excellent' : ($stockStatus === 'attention' ? 'Stock faible' : 'Rupture de stock') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl border border-purple-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                            <i class="bi bi-calendar-plus text-lg text-purple-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Créé le</p>
                                            <p class="font-semibold text-gray-800">{{ $product->created_at?->format('d/m/Y') ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $product->created_at?->format('H:i') ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl border border-orange-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                                            <i class="bi bi-calendar-check text-lg text-orange-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Modifié le</p>
                                            <p class="font-semibold text-gray-800">{{ $product->updated_at?->format('d/m/Y') ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $product->updated_at?->format('H:i') ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description & Actions -->
            <div class="space-y-6">
                <!-- Description Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="bi bi-text-paragraph text-orange-500"></i>
                            Description
                        </h3>
                        <div class="p-4 bg-gray-50 rounded-xl min-h-[120px]">
                            @if($product->description)
                                <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-400 h-24">
                                    <i class="bi bi-text-left text-3xl mb-2"></i>
                                    <p class="text-sm">Aucune description</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="bi bi-lightning-charge text-purple-500"></i>
                            Actions rapides
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('products.edit', $product->id) }}" class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-800 font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                                <i class="bi bi-pencil-square group-hover:rotate-12 transition-transform duration-300"></i>
                                <span>Modifier le produit</span>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                                    <i class="bi bi-trash group-hover:scale-110 transition-transform duration-300"></i>
                                    <span>Supprimer le produit</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Product ID -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 rounded-2xl p-6 text-white text-center">
                    <p class="text-sm text-gray-300 mb-1">ID du produit</p>
                    <p class="text-2xl font-bold font-mono">{{ $product->id }}</p>
                    <p class="text-xs text-gray-400 mt-2">Identifiant unique</p>
                </div>
            </div>
        </div>

        <!-- Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <a href="{{ route('products.index') }}" class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-6 border border-gray-100 group hover:border-blue-200 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 group-hover:bg-blue-500 transition-colors duration-200 flex items-center justify-center">
                        <i class="bi bi-grid-3x3-gap text-blue-600 group-hover:text-white text-xl transition-colors duration-200"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200">Tous les produits</h3>
                        <p class="text-sm text-gray-500">Voir l'inventaire complet</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.create') }}" class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-6 border border-gray-100 group hover:border-green-200 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 group-hover:bg-green-500 transition-colors duration-200 flex items-center justify-center">
                        <i class="bi bi-plus-circle text-green-600 group-hover:text-white text-xl transition-colors duration-200"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-semibold text-gray-800 group-hover:text-green-600 transition-colors duration-200">Nouveau produit</h3>
                        <p class="text-sm text-gray-500">Ajouter un produit</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.edit', $product->id) }}" class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-6 border border-gray-100 group hover:border-yellow-200 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-100 group-hover:bg-yellow-500 transition-colors duration-200 flex items-center justify-center">
                        <i class="bi bi-pencil-square text-yellow-600 group-hover:text-white text-xl transition-colors duration-200"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-semibold text-gray-800 group-hover:text-yellow-600 transition-colors duration-200">Éditer</h3>
                        <p class="text-sm text-gray-500">Modifier ce produit</p>
                    </div>
                </div>
            </a>
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
</style>
@endsection