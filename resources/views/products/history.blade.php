@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- En-tête -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-history mr-2"></i>Historique des mouvements
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Produit: <span class="font-semibold">{{ $product->name }}</span>
                            | Stock actuel: <span class="font-semibold {{ $product->stock <= 10 ? 'text-red-600' : 'text-green-600' }}">{{ $product->stock }}</span>
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('products.show', $product) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                        <a href="{{ route('products.global-history') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                            <i class="fas fa-globe mr-2"></i> Historique global
                        </a>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="text-sm text-blue-800 dark:text-blue-300">Stock actuel</div>
                        <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $product->stock }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="text-sm text-green-800 dark:text-green-300">Total entrées</div>
                        <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                            {{ $totals['entree']->total_quantity ?? 0 }}
                        </div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-200 dark:border-red-800">
                        <div class="text-sm text-red-800 dark:text-red-300">Total sorties</div>
                        <div class="text-2xl font-bold text-red-900 dark:text-red-100">
                            {{ $totals['sortie']->total_quantity ?? 0 }}
                        </div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                        <div class="text-sm text-purple-800 dark:text-purple-300">Total mouvements</div>
                        <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $movements->total() }}</div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg mb-6">
                    <form method="GET" action="{{ route('products.history', $product) }}" class="space-y-4 md:space-y-0 md:flex md:space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type de mouvement</label>
                            <select name="type" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Tous les types</option>
                                <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrées</option>
                                <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sorties</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de début</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de fin</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                <i class="fas fa-filter mr-2"></i>Filtrer
                            </button>
                            @if(request()->anyFilled(['type', 'start_date', 'end_date']))
                                <a href="{{ route('products.history', $product) }}" class="ml-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md">
                                    <i class="fas fa-times mr-2"></i>Effacer
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Tableau des mouvements -->
                @if($movements->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun mouvement enregistré</h3>
                        <p class="text-gray-600 dark:text-gray-400">Aucun mouvement de stock n'a été enregistré pour ce produit.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock après</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Motif</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Utilisateur</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($movements as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $movement->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($movement->type == 'entree')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <i class="fas fa-arrow-down mr-1"></i>Entrée
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <i class="fas fa-arrow-up mr-1"></i>Sortie
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($movement->type == 'entree')
                                            <span class="text-green-600 dark:text-green-400">+{{ $movement->quantity }}</span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400">-{{ $movement->quantity }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $movement->stock_after }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $movement->motif }}
                                        @if($movement->reference_document)
                                            <br>
                                            <small class="text-gray-500 dark:text-gray-400">
                                                Ref: {{ $movement->reference_document }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $movement->user->name ?? 'Système' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $movements->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- CSS pour les icônes FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection