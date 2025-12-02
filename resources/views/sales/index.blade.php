@extends('layouts.app')

@section('title', 'Liste des ventes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                        <span class="text-4xl">💰</span>
                        Historique des ventes
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Suivez l'ensemble des transactions de votre activité</p>
                </div>
                <a href="{{ route('sales.create') }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                    <i class="bi bi-plus-circle text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Nouvelle vente</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total ventes</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $sales->total() }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-receipt text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Chiffre d'affaires</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($sales->sum('total_price'), 0, ',', ' ') }}</h3>
                        <p class="text-xs text-green-100 mt-1">FCFA</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-currency-dollar text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Quantité vendue</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $sales->sum('quantity') }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-box-seam text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Vente moyenne</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($sales->avg('total_price') ?? 0, 0, ',', ' ') }}</h3>
                        <p class="text-xs text-orange-100 mt-1">FCFA</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-graph-up text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Quantité</th>
                            @can('admin')
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Prix d'achat</th>
                            @endcan
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Caissier</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-100 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                                <!-- ID -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm group-hover:bg-blue-100 group-hover:text-blue-700 transition-colors">
                                        {{ $sale->id }}
                                    </span>
                                </td>

                                <!-- Produits -->
                                <td class="px-6 py-4">
                                    @foreach($sale->items as $item)
                                        <div class="flex items-center gap-3 mb-1">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold shadow-md">
                                                {{ substr($item->product?->name ?? 'X', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $item->product?->name ?? 'Produit supprimé' }}</p>
                                                <p class="text-xs text-gray-500">Produit #{{ $item->product_id }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>

                                <!-- Client -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-sm font-bold">
                                            {{ substr($sale->client?->name ?? 'X', 0, 1) }}
                                        </div>
                                        <span class="font-medium text-gray-700">{{ $sale->client?->name ?? 'Client inconnu' }}</span>
                                    </div>
                                </td>

                                <!-- Quantité totale -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $totalQuantity = $sale->items->sum('quantity');
                                    @endphp
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold border border-blue-200">
                                        <i class="bi bi-box-seam text-xs"></i>
                                        {{ $totalQuantity }}
                                    </span>
                                </td>

                                <!-- Prix d'achat (admin) -->
                                @can('admin')
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $totalPurchasePrice = $sale->items->sum(fn($item) => $item->unit_price * $item->quantity);
                                        @endphp
                                        <div class="flex flex-col">
                                            <span class="text-gray-800 font-semibold">{{ number_format($totalPurchasePrice, 0, ',', ' ') }}</span>
                                            <span class="text-xs text-gray-500">FCFA</span>
                                        </div>
                                    </td>
                                @endcan

                                <!-- Total vente -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-bold text-gray-800">{{ number_format($sale->total_price, 0, ',', ' ') }}</span>
                                        <span class="text-xs text-gray-500">FCFA</span>
                                    </div>
                                </td>

                                <!-- Caissier -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                                            {{ substr($sale->user?->name ?? 'X', 0, 1) }}
                                        </div>
                                        <span class="text-gray-700">{{ $sale->user?->name ?? 'Utilisateur inconnu' }}</span>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <i class="bi bi-calendar3 text-gray-400"></i>
                                            {{ $sale->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                            <i class="bi bi-clock text-gray-400"></i>
                                            {{ $sale->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('sales.show', $sale->id) }}" class="inline-flex items-center justify-center w-9 h-9 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" title="Voir détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette vente ?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-9 h-9 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-receipt text-5xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune vente enregistrée</h3>
                                        <p class="text-gray-500 mb-6">Commencez par enregistrer votre première vente</p>
                                        <a href="{{ route('sales.create') }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                            <i class="bi bi-plus-circle"></i>
                                            <span>Créer la première vente</span>
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
        @if($sales->hasPages())
            <div class="mt-6 bg-white rounded-xl shadow-md p-4">
                {{ $sales->links() }}
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                <div class="flex items-start gap-3">
                    <i class="bi bi-graph-up-arrow text-2xl text-blue-500 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-blue-800 mb-2">Performance des ventes</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-blue-700 font-semibold">{{ number_format($sales->max('total_price') ?? 0, 0, ',', ' ') }} FCFA</p>
                                <p class="text-blue-600 text-xs">Plus grosse vente</p>
                            </div>
                            <div>
                                <p class="text-blue-700 font-semibold">{{ number_format($sales->min('total_price') ?? 0, 0, ',', ' ') }} FCFA</p>
                                <p class="text-blue-600 text-xs">Plus petite vente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                <div class="flex items-start gap-3">
                    <i class="bi bi-people text-2xl text-green-500 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-green-800 mb-2">Répartition</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-green-700 font-semibold">{{ $sales->unique('user_id')->count() }}</p>
                                <p class="text-green-600 text-xs">Caissiers actifs</p>
                            </div>
                            <div>
                                <p class="text-green-700 font-semibold">{{ $sales->unique('client_id')->count() }}</p>
                                <p class="text-green-600 text-xs">Clients uniques</p>
                            </div>
                        </div>
                    </div>
                </div>
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

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

/* Style pour la pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.pagination .page-item .page-link {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
    color: #374151;
    text-decoration: none;
    transition: all 0.2s;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-color: #3b82f6;
    color: white;
}

.pagination .page-item .page-link:hover {
    background-color: #f3f4f6;
}
</style>
@endsection