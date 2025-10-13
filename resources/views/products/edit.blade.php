<!-- resources/views/products/edit.blade.php -->

@extends('layouts.app')

@section('title', 'Modifier le produit : ' . $product->name)

@section('content')
<div class="container mx-auto mt-8 px-4">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">✏️ Modifier le produit</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block font-semibold text-gray-700 mb-1">Nom du produit</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="price" class="block font-semibold text-gray-700 mb-1">Prix (CFA)</label>
                <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="stock" class="block font-semibold text-gray-700 mb-1">Stock</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="description" class="block font-semibold text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded flex items-center">
                    <i class="bi bi-arrow-left-circle mr-2"></i> Retour
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded flex items-center">
                    <i class="bi bi-save mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
