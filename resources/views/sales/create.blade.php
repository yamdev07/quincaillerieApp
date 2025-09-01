@extends('layouts.app')

@section('title', 'Nouvelle vente')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🛒 Enregistrer une nouvelle vente</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="product_id" class="form-label">Produit</label>
            <select name="product_id" id="product_id" class="form-select" required>
                <option value="">-- Choisir un produit --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} ({{ number_format($product->price, 0, ',', ' ') }} FCFA)</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <select name="client_id" id="client_id" class="form-select">
                <option value="">-- Client inconnu --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantité</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer la vente</button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
