@extends('layouts.app')

@section('title', 'Fournisseurs')

@section('content')
<div class="container mt-4">
    <h2>📦 Liste des fournisseurs</h2>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">➕ Nouveau fournisseur</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Contact</th>
                <th>Téléphone</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun fournisseur trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $suppliers->links() }}
</div>
@endsection
