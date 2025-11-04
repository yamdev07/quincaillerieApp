@extends('layouts.app')

@section('title', 'Fournisseurs')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des fournisseurs</h1>
            <p class="text-gray-600 mt-2">Consultez et gérez vos partenaires fournisseurs</p>
        </div>

        @if(Auth::user() && Auth::user()->role === 'admin')
            <a href="{{ route('suppliers.create') }}" class="bg-gradient-to-r from-orange-600 to-amber-600 text-white px-5 py-3 rounded-lg shadow-md hover:from-orange-700 hover:to-amber-700 transition-all duration-300 flex items-center">
                <i class="fas fa-truck-loading mr-2"></i>
                Nouveau fournisseur
            </a>
        @endif
    </div>

    <!-- Alerts Section -->
    @if(session('success'))
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg mb-6 border border-green-200 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                    <i class="fas fa-truck text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Fournisseurs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $suppliers->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-calendar-day text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Ajoutés ce mois</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $suppliers->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-user-tie text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Avec contact</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $suppliers->where('contact', '!=', null)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-phone text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Avec téléphone</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $suppliers->where('phone', '!=', null)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="glass-card rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-orange-50 to-amber-50 border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fournisseur</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Coordonnées</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date d'ajout</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suppliers as $supplier)
                        <tr class="table-row-hover transition-all duration-200">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-orange-500 to-amber-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $supplier->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="space-y-2">
                                    @if($supplier->contact)
                                    <div class="flex items-center text-sm text-gray-900">
                                        <i class="fas fa-user-tie text-gray-400 mr-2 text-xs"></i>
                                        {{ $supplier->contact }}
                                    </div>
                                    @endif
                                    @if($supplier->phone)
                                    <div class="flex items-center text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-2 text-xs"></i>
                                        {{ $supplier->phone }}
                                    </div>
                                    @endif
                                    @if(!$supplier->contact && !$supplier->phone)
                                    <span class="text-xs text-gray-500 italic">Aucune coordonnée</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $supplier->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $supplier->created_at->format('H:i') }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-center">
                                @if(Auth::user() && Auth::user()->role === 'admin')
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="bg-blue-100 text-blue-600 hover:bg-blue-200 px-3 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                            <i class="fas fa-edit mr-1 text-sm"></i>
                                            <span class="text-sm">Éditer</span>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce fournisseur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                                <i class="fas fa-trash-alt mr-1 text-sm"></i>
                                                <span class="text-sm">Supprimer</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 px-6 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-truck text-gray-300 text-4xl mb-3"></i>
                                    <h3 class="text-lg font-medium text-gray-700">Aucun fournisseur trouvé</h3>
                                    <p class="text-gray-500 mt-1">Commencez par ajouter votre premier fournisseur</p>
                                    @if(Auth::user() && Auth::user()->role === 'admin')
                                        <a href="{{ route('suppliers.create') }}" class="mt-4 bg-gradient-to-r from-orange-600 to-amber-600 text-white px-4 py-2 rounded-lg hover:from-orange-700 hover:to-amber-700 transition-all duration-300">
                                            Ajouter un fournisseur
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($suppliers->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
