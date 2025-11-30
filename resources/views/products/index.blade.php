<!-- resources/views/products/index.blade.php -->

@extends('layouts.app')

@section('title', 'Produits')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
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
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($products->sum(fn($p) => $p->price * $p->stock), 0, ',', ' ') }}</h3>
                        <p class="text-xs text-purple-100 mt-1">CFA</p>
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
                                <!-- Prix de vente -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-bold text-gray-800">{{ isset($product->price) ? number_format($product->price, 0, ',', ' ') : '0' }}</span>
                                        <span class="text-xs text-gray-500">CFA</span>
                                    </div>
                                </td>
                                <!-- Prix d'achat (admin seulement) -->
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
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Bouton Voir (accessible à tout le monde) -->
                                        <a href="{{ route('products.show', $product->id) }}" 
                                        class="inline-flex items-center justify-center w-9 h-9 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                                        title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Boutons Modifier et Supprimer (seulement pour admin) -->
                                        @if(Auth::user() && Auth::user()->role === 'admin')
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                            class="inline-flex items-center justify-center w-9 h-9 bg-yellow-400 hover:bg-yellow-500 text-gray-800 rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                                            title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('products.destroy', $product->id) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ?')" 
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-9 h-9 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                                                        title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-box-seam text-5xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun produit trouvé</h3>
                                        <p class="text-gray-500 mb-6">Commencez par créer votre premier produit</p>
                                        <a href="{{ route('products.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                            <i class="bi bi-plus-circle"></i>
                                            <span>Créer le premier produit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 bg-white rounded-xl shadow-md p-4">
            {{ $products->links() }}
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