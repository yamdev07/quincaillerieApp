@extends('layouts.app')

@section('title', 'Détails de la vente')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-800 to-blue-600 bg-clip-text text-transparent flex items-center gap-3">
                        <i class="bi bi-receipt text-4xl text-blue-600"></i>
                        Vente #{{ $sale->id }}
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Détails de la transaction</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('sales.invoice', $sale->id) }}" 
                       class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                        <i class="bi bi-printer"></i>
                        Facture
                    </a>
                    <a href="{{ route('sales.index') }}" 
                       class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                        <i class="bi bi-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations générales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Montant total</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($sale->total_price, 0, ',', ' ') }}</h3>
                        <p class="text-xs text-blue-100 mt-1">FCFA</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-cash-stack text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Quantité totale</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $totalQuantity }}</h3>
                        <p class="text-xs text-green-100 mt-1">Articles</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-box-seam text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Date de vente</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $sale->created_at->format('d/m/Y') }}</h3>
                        <p class="text-xs text-purple-100 mt-1">{{ $sale->created_at->format('H:i:s') }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-calendar-date text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails de la vente -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Informations client -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-circle text-blue-600"></i>
                    Informations client
                </h3>
                @if($sale->client)
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($sale->client->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $sale->client->name }}</p>
                                <p class="text-sm text-gray-600">{{ $sale->client->email ?? 'Pas d\'email' }}</p>
                            </div>
                        </div>
                        @if($sale->client->phone)
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="bi bi-telephone text-blue-500"></i>
                                <span>{{ $sale->client->phone }}</span>
                            </div>
                        @endif
                        @if($sale->client->address)
                            <div class="flex items-start gap-2 text-gray-600">
                                <i class="bi bi-geo-alt text-blue-500 mt-1"></i>
                                <span>{{ $sale->client->address }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-person-x text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Vente sans client enregistré</p>
                    </div>
                @endif
            </div>

            <!-- Informations caissier -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-badge text-purple-600"></i>
                    Informations caissier
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($sale->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $sale->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $sale->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="bi bi-person-workspace text-purple-500"></i>
                        <span class="capitalize">{{ $sale->user->role }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="bi bi-cart-check text-orange-600"></i>
                Produits vendus
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Prix unitaire</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sale->items as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($item->product->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-500">Réf: {{ $item->product->reference ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-800 font-medium">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-800 font-bold">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-800 font-bold text-lg">{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-800">TOTAL</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                <div class="text-gray-600 text-sm">
                    <i class="bi bi-info-circle text-blue-500 mr-2"></i>
                    Vente créée le {{ $sale->created_at->format('d/m/Y à H:i') }}
                </div>
                <div class="flex gap-3">
                    @can('admin')
                        <form action="{{ route('sales.destroy', $sale->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette vente ? Cette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                                <i class="bi bi-trash"></i>
                                Supprimer
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection