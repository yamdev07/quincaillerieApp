@extends('layouts.app')

@section('title', 'Employés')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Gestion des employés</h1>
        <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter un employé</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    <table class="min-w-full bg-white border border-gray-200 rounded shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Nom</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Rôle</th>
                <th class="py-2 px-4 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                    <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                    <td class="py-2 px-4 border-b">{{ ucfirst($user->role) }}</td>
                    <td class="py-2 px-4 border-b text-center flex justify-center gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-400 text-black px-2 py-1 rounded hover:bg-yellow-500">Éditer</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ?');">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Aucun employé trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
