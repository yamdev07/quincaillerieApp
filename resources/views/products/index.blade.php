<!-- resources/views/products/index.blade.php -->

@extends('layouts.app')

@section('title', 'Produits')

@section('content')
<div class="container mx-auto mt-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📦 Liste des produits</h2>
        <a href="{{ route('products.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow-lg flex items-center">
            <i class="bi bi-plus-circle mr-2"></i> Nouveau produit
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" onclick="this.parentElement.parentElement.remove();" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.36 5.652a.5.5 0 10-.707.707L9.293 10l-3.64 3.64a.5.5 0 00.707.707L10 10.707l3.64 3.64a.5.5 0 00.707-.707L10.707 10l3.64-3.64a.5.5 0 000-.708z"/></svg>
            </span>
        </div>
    @endif

    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="table table-striped table-hover min-w-full bg-white rounded-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Prix (CFA)</th>
                    <th class="px-6 py-3 text-left">Stock</th>
                    <th class="px-6 py-3 text-left">Date création</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-6 py-4">{{ $product->id ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $product->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ isset($product->price) ? number_format($product->price, 2, ',', ' ') : 'CFA' }}</td>
                        <td class="px-6 py-4">
                            @php $stock = $product->stock ?? 0; @endphp
                            <span class="px-2 py-1 rounded-full text-white {{ $stock > 10 ? 'bg-green-600' : ($stock > 0 ? 'bg-yellow-400 text-black' : 'bg-red-600') }}">
                                {{ $stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $product->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('products.show', $product->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-black px-2 py-1 rounded" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            <p class="mb-2">Aucun produit trouvé</p>
                            <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Créer le premier produit</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
