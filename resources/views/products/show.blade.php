@extends('layouts.app')

@section('title', 'Produit : ' . $product->name)

@section('content')
<div class="container mx-auto mt-8 px-4">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-3xl font-bold mb-4 text-gray-800">📦 {{ $product->name }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-700"><strong>Prix :</strong> {{ isset($product->price) ? number_format($product->price, 2, ',', ' ') : 'CFA' }} €</p>
                <p class="text-gray-700"><strong>Stock :</strong> 
                    <span class="px-2 py-1 rounded-full text-white {{ $product->stock > 10 ? 'bg-green-600' : ($product->stock > 0 ? 'bg-yellow-400 text-black' : 'bg-red-600') }}">
                        {{ $product->stock ?? 0 }}
                    </span>
                </p>
                <p class="text-gray-700"><strong>Date création :</strong> {{ $product->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-700"><strong>Description :</strong></p>
                <p class="text-gray-600">{{ $product->description ?? 'Aucune description disponible.' }}</p>
            </div>
        </div>

        <div class="mt-6 flex justify-start space-x-2">
            <a href="{{ route('products.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center">
                <i class="bi bi-arrow-left-circle mr-2"></i> Retour à la liste
            </a>
            <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded flex items-center">
                <i class="bi bi-pencil-square mr-2"></i> Modifier
            </a>
        </div>
    </div>
</div>
@endsection
