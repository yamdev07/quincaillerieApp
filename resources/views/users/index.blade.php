@extends('layouts.app')

@section('title', 'Employés')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des employés</h1>
            <p class="text-gray-600 mt-2">Gérez les comptes et les permissions de vos collaborateurs</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-3 rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 flex items-center">
            <i class="fas fa-user-plus mr-2"></i>
            Ajouter un employé
        </a>
    </div>

    <!-- Alerts Section -->
    @if(session('success'))
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg mb-6 border border-green-200 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-lg mb-6 border border-red-200 flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Table Section -->
    <div class="glass-card rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Employé</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rôle</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="table-row-hover transition-all duration-200">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                @php
                                    $roleClass = 'role-user';
                                    if($user->role === 'admin') $roleClass = 'role-admin';
                                    elseif($user->role === 'manager') $roleClass = 'role-manager';
                                @endphp
                                <span class="role-badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="bg-blue-100 text-blue-600 hover:bg-blue-200 px-3 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                        <i class="fas fa-edit mr-1 text-sm"></i>
                                        <span class="text-sm">Éditer</span>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                            <i class="fas fa-trash-alt mr-1 text-sm"></i>
                                            <span class="text-sm">Supprimer</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 px-6 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                    <h3 class="text-lg font-medium text-gray-700">Aucun employé trouvé</h3>
                                    <p class="text-gray-500 mt-1">Commencez par ajouter votre premier employé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .role-admin {
        background-color: #fef3c7;
        color: #92400e;
    }
    .role-manager {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .role-user {
        background-color: #dcfce7;
        color: #166534;
    }
    .table-row-hover:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
@endsection