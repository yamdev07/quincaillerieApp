@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="container mt-4">
    <h2>👥 Liste des clients</h2>
    <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">➕ Nouveau client</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun client trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $clients->links() }}
</div>
@endsection
