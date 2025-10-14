@extends('layouts.app')

@section('title', 'Détails de la vente #' . $sale->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            Vente #{{ $sale->id }}
                        </h2>
                        <p class="text-gray-500 mt-1 text-sm">Détails complets de la transaction</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('sales.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-300"></i>
                        <span>Retour aux ventes</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Transaction Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                            <i class="bi bi-info-circle text-blue-500"></i>
                            Détails de la transaction
                        </h3>
                        
                        <div class="space-y-6">
                            <!-- Product Information -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">
                                        <i class="bi bi-box-seam text-2xl text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Produit vendu</p>
                                        <p class="text-xl font-bold text-gray-800">{{ $sale->product->name }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $sale->product->id }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Prix unitaire</p>
                                    <p class="text-lg font-semibold text-gray-800">
                                        {{ number_format($sale->total_price / $sale->quantity, 2, ',', ' ') }}
                                        <span class="text-sm text-gray-500">FCFA</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Quantity & Total -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                                            <i class="bi bi-123 text-lg text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Quantité vendue</p>
                                            <p class="text-2xl font-bold text-gray-800">{{ $sale->quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl border border-purple-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                                            <i class="bi bi-currency-dollar text-lg text-purple-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Montant total</p>
                                            <p class="text-2xl font-bold text-gray-800">{{ number_format($sale->total_price, 0, ',', ' ') }}</p>
                                            <p class="text-xs text-gray-500">FCFA</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Client Information -->
                            <div class="p-4 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl border border-orange-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-xl bg-orange-100 flex items-center justify-center">
                                        <i class="bi bi-person-badge text-2xl text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Client</p>
                                        <p class="text-xl font-bold text-gray-800">
                                            {{ $sale->client ? $sale->client->name : 'Client inconnu' }}
                                        </p>
                                        @if($sale->client)
                                            <p class="text-xs text-gray-500">ID: {{ $sale->client->id }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Cashier Information -->
                            <div class="p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-xl bg-indigo-100 flex items-center justify-center">
                                        <i class="bi bi-person-check text-2xl text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Caissier</p>
                                        <p class="text-xl font-bold text-gray-800">{{ $sale->user->name }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $sale->user->id }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="space-y-6">
                <!-- Timeline Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="bi bi-clock-history text-purple-500"></i>
                            Historique
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="bi bi-calendar-plus text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Date de création</p>
                                    <p class="text-sm text-gray-600">{{ $sale->created_at->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $sale->created_at->format('H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="bi bi-calendar-check text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Dernière modification</p>
                                    <p class="text-sm text-gray-600">{{ $sale->updated_at->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $sale->updated_at->format('H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="bi bi-lightning-charge text-yellow-500"></i>
                            Actions rapides
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('sales.edit', $sale->id) }}" class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-800 font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                                <i class="bi bi-pencil-square group-hover:rotate-12 transition-transform duration-300"></i>
                                <span>Modifier la vente</span>
                            </a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette vente ? Cette action est irréversible.')" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                                    <i class="bi bi-trash group-hover:scale-110 transition-transform duration-300"></i>
                                    <span>Supprimer la vente</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sale ID Card -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 rounded-2xl p-6 text-white text-center">
                    <p class="text-sm text-gray-300 mb-1">ID de transaction</p>
                    <p class="text-2xl font-bold font-mono">{{ $sale->id }}</p>
                    <p class="text-xs text-gray-400 mt-2">Identifiant unique</p>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Product Details -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-box-seam text-blue-500"></i>
                    Informations produit
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nom du produit:</span>
                        <span class="font-semibold">{{ $sale->product->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Prix unitaire:</span>
                        <span class="font-semibold">{{ number_format($sale->total_price / $sale->quantity, 2, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Stock actuel:</span>
                        <span class="font-semibold {{ $sale->product->stock > 10 ? 'text-green-600' : ($sale->product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $sale->product->stock }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Catégorie:</span>
                        <span class="font-semibold">{{ $sale->product->category ?? 'Non définie' }}</span>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-calculator text-green-500"></i>
                    Résumé financier
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sous-total:</span>
                        <span class="font-semibold">{{ number_format($sale->total_price, 2, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Quantité:</span>
                        <span class="font-semibold">{{ $sale->quantity }} unités</span>
                    </div>
                    <div class="border-t pt-3 mt-3">
                        <div class="flex justify-between text-lg">
                            <span class="font-bold text-gray-800">Total:</span>
                            <span class="font-bold text-green-600">{{ number_format($sale->total_price, 2, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <a href="{{ route('sales.index') }}" class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-6 border border-gray-100 group hover:border-blue-200 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 group-hover:bg-blue-500 transition-colors duration-200 flex items-center justify-center">
                        <i class="bi bi-grid-3x3-gap text-blue-600 group-hover:text-white text-xl transition-colors duration-200"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-200">Toutes les ventes</h3>
                        <p class="text-sm text-gray-500">Voir l'historique complet</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('sales.create') }}" class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-6 border border-gray-100 group hover:border-green-200 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 group-hover:bg-green-500 transition-colors duration-200 flex items-center justify-center">
                        <i class="bi bi-plus-circle text-green-600 group-hover:text-white text-xl transition-colors duration-200"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-semibold text-gray-800 group-hover:text-green-600 transition-colors duration-200">Nouvelle vente</h3>
                        <p class="text-sm text-gray-500">Créer une transaction</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.show', $sale->product->id) }}" class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-6 border border-gray-100 group hover:border-purple-200 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 group-hover:bg-purple-500 transition-colors duration-200 flex items-center justify-center">
                        <i class="bi bi-box-arrow-up-right text-purple-600 group-hover:text-white text-xl transition-colors duration-200"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-semibold text-gray-800 group-hover:text-purple-600 transition-colors duration-200">Voir le produit</h3>
                        <p class="text-sm text-gray-500">Détails du produit</p>
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