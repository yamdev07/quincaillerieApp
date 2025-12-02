@extends('layouts.app')

@section('content')
<h1>Liste des catégories</h1>

{{-- Bouton "Ajouter" visible uniquement pour l’admin --}}
@if(auth()->user()->role === 'admin')
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Ajouter une catégorie</a>
@endif

<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Sous-nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->sub_name }}</td>

            <td>
                {{-- Actions uniquement pour admin --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                        Modifier
                    </a>

                    <form action="{{ route('categories.destroy', $category->id) }}"
                        method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Supprimer ?')">
                            Supprimer
                        </button>
                    </form>
                @else
                    <span class="text-muted">Lecture seule</span>
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
@endsection
