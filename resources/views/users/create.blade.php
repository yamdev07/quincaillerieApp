@extends('layouts.app')

@section('title', 'Ajouter un employé')

@section('content')
<div class="container mx-auto py-8 max-w-lg">
    <h1 class="text-2xl font-bold mb-6">Ajouter un nouvel employé</h1>

    <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Nom</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border px-3 py-2 rounded" value="{{ old('email') }}" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Mot de passe</label>
            <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Rôle</label>
            <select name="role" class="w-full border px-3 py-2 rounded" required>
                <option value="admin">Admin</option>
                <option value="magasinier">Magasinier</option>
                <option value="caissier">Caissier</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</button>
    </form>
</div>
@endsection
