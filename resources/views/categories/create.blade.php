@extends('layouts.app')

@section('content')
<h1>{{ isset($category) ? 'Modifier' : 'Ajouter' }} une catégorie</h1>

{{-- Vérification : seul un admin peut créer ou modifier --}}
@if(auth()->user()->role !== 'admin')
    <div class="alert alert-danger">
        Vous n'avez pas l'autorisation d'accéder à cette section.
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-primary">Retour</a>
    @php return; @endphp
@endif

<form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
    @csrf
    @if(isset($category))
        @method('PUT')
    @endif

    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="name" class="form-control"
               value="{{ $category->name ?? old('name') }}" required>
    </div>

    <div class="form-group">
        <label>Sous-nom</label>
        <input type="text" name="sub_name" class="form-control"
               value="{{ $category->sub_name ?? old('sub_name') }}" required>
    </div>

    <button type="submit" class="btn btn-success mt-2">
        {{ isset($category) ? 'Mettre à jour' : 'Ajouter' }}
    </button>
</form>
@endsection
