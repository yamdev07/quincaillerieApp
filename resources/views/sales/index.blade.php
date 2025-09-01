@extends('layouts.app')

@section('title', 'Liste des ventes')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📦 Liste des ventes</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Produit</th>
                <th>Client</th>
                <th>Quantité</th>
                <th>Total (FCFA)</th>
                <th>Caissier</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->product->name }}</td>
                    <td>{{ $sale->client ? $sale->client->name : 'Client inconnu' }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ number_format($sale->total_price, 0, ',', ' ') }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $sales->links() }}
    </div>
</div>
@endsection
