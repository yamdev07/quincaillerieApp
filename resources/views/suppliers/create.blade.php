@extends('layouts.app')

@section('title', 'Ajouter Fournisseur')

@section('content')
<div class="container mt-4">
    <h2>➕ Ajouter un fournisseur</h2>
    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nom *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <button class="btn btn-success">Enregistrer</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
