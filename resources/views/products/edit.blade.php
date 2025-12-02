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

            <!-- Nom -->
            <div>
                <label for="name" class="block font-semibold text-gray-700 mb-1">Nom du produit</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Prix d'achat -->
            <div>
                <label for="purchase_price" class="block font-semibold text-gray-700 mb-1">Prix d'achat (CFA)</label>
                <input type="number" step="0.01" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Prix de vente -->
            <div>
                <label for="sale_price" class="block font-semibold text-gray-700 mb-1">Prix de vente (CFA)</label>
                <input type="number" step="0.01" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Stock -->
            <div>
                <label for="stock" class="block font-semibold text-gray-700 mb-1">Stock</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Catégorie -->
            <div>
                <label for="category_id" class="block font-semibold text-gray-700 mb-1">Catégorie</label>
                <select id="category_id" name="category_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fournisseur -->
            <div>
                <label for="supplier_id" class="block font-semibold text-gray-700 mb-1">Fournisseur</label>
                <select id="supplier_id" name="supplier_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Sélectionner un fournisseur</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Description -->
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
