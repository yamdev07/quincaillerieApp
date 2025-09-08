@extends('layouts.app')

@section('title', 'Ajouter Client')

@section('content')
<div class="container mt-4">
    <h2>➕ Ajouter un client</h2>
    <form method="POST" action="{{ route('clients.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nom *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <button class="btn btn-success">Enregistrer</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
