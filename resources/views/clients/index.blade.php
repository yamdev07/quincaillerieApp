@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des clients</h1>
            <p class="text-gray-600 mt-2">Consultez et gérez votre base de clients</p>
        </div>

        @if(Auth::user() && Auth::user()->role === 'admin')
            <a href="{{ route('clients.create') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-5 py-3 rounded-lg shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 flex items-center">
                <i class="fas fa-user-plus mr-2"></i>
                Nouveau client
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
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Clients</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $clients->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-calendar-day text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Aujourd'hui</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $clients->where('created_at', '>=', today())->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-envelope text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Avec email</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $clients->where('email', '!=', null)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                    <i class="fas fa-phone text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Avec téléphone</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $clients->where('phone', '!=', null)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="glass-card rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Coordonnées</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date d'inscription</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clients as $client)
                        <tr class="table-row-hover transition-all duration-200">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $client->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="space-y-1">
                                    @if($client->email)
                                    <div class="flex items-center text-sm text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-2 text-xs"></i>
                                        {{ $client->email }}
                                    </div>
                                    @endif
                                    @if($client->phone)
                                    <div class="flex items-center text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-2 text-xs"></i>
                                        {{ $client->phone }}
                                    </div>
                                    @endif
                                    @if(!$client->email && !$client->phone)
                                    <span class="text-xs text-gray-500 italic">Aucune coordonnée</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $client->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $client->created_at->format('H:i') }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-center">
                                @if(Auth::user() && Auth::user()->role === 'admin')
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('clients.edit', $client->id) }}" class="bg-blue-100 text-blue-600 hover:bg-blue-200 px-3 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                            <i class="fas fa-edit mr-1 text-sm"></i>
                                            <span class="text-sm">Éditer</span>
                                        </a>
                                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce client ?');">
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
                                    <i class="fas fa-user-friends text-gray-300 text-4xl mb-3"></i>
                                    <h3 class="text-lg font-medium text-gray-700">Aucun client trouvé</h3>
                                    <p class="text-gray-500 mt-1">Commencez par ajouter votre premier client</p>
                                    @if(Auth::user() && Auth::user()->role === 'admin')
                                        <a href="{{ route('clients.create') }}" class="mt-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-300">
                                            Ajouter un client
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
        @if($clients->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $clients->links() }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .table-row-hover:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
@endsection
