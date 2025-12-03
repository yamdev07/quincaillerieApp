@extends('layouts.app')

@section('title', 'Modifier la catégorie : ' . $category->name)

@section('styles')
<style>
    .form-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: calc(100vh - 64px);
    }
    
    .input-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
    }
    
    .animated-input:focus + .input-icon {
        color: #3b82f6;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .card-hover {
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #3b82f6;
    }
</style>
@endsection

@section('content')
<div class="form-container py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Bouton Retour -->
        <div class="mb-4">
                <a href="{{ route('categories.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2 group">                
                <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
                <span>Retour aux catégories</span>
            </a>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                <i class="bi bi-pencil-square text-3xl text-blue-600"></i>
                Modifier la catégorie
            </h1>
            <p class="text-gray-600 mt-2 flex items-center gap-2">
                <span class="font-semibold text-blue-700">{{ $category->name }}</span>
                • ID: <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-sm font-medium">{{ $category->id }}</span>
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden card-hover">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="bi bi-tags text-xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Modifier les informations</h2>
                        <p class="text-blue-100 text-sm">Mettez à jour les détails de la catégorie</p>
                    </div>
                </div>
            </div>

            <!-- Messages d'erreur -->
            @if($errors->any())
                <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 mx-6 mt-4 p-4 rounded-lg animate-fade-in">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-exclamation-triangle-fill text-2xl text-red-600"></i>
                        <div>
                            <p class="font-semibold text-red-800">Veuillez corriger les erreurs suivantes :</p>
                            <ul class="list-disc list-inside text-red-700 text-sm mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 mx-6 mt-4 rounded-xl relative" role="alert">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-check-circle-fill text-2xl text-green-600"></i>
                        <div>
                            <p class="font-semibold">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Content -->
            <form action="{{ route('categories.update', $category->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Nom de la catégorie -->
                    <div class="relative">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-tag mr-2"></i>Nom de la catégorie
                        </label>
                        <div class="relative">
                            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 animated-input"
                                   placeholder="Ex: Outils électriques" required>
                            <div class="input-icon">
                                <i class="bi bi-tag"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Nom principal de la catégorie</p>
                    </div>

                    <!-- Sous-nom (optionnel) -->
                    <div class="relative">
                        <label for="sub_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-tags mr-2"></i>Sous-catégorie (optionnel)
                        </label>
                        <div class="relative">
                            <input type="text" id="sub_name" name="sub_name" value="{{ old('sub_name', $category->sub_name) }}"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 animated-input"
                                   placeholder="Ex: Marteaux, tournevis, clés">
                            <div class="input-icon">
                                <i class="bi bi-tags"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Détails ou spécificités de la catégorie</p>
                    </div>

                    <!-- Description (optionnel) -->
                    <div class="relative">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-card-text mr-2"></i>Description (optionnel)
                        </label>
                        <div class="relative">
                            <textarea id="description" name="description" rows="4"
                                      class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 animated-input resize-none"
                                      placeholder="Décrivez cette catégorie en détail...">{{ old('description', $category->description) }}</textarea>
                            <div class="absolute left-3 top-3">
                                <i class="bi bi-card-text text-gray-400"></i>
                            </div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-gray-500">Informations supplémentaires sur la catégorie</p>
                            <p class="text-xs text-gray-400" id="charCount">0/500 caractères</p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques (lecture seule) -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-graph-up"></i>
                        Statistiques de la catégorie
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-sm">Produits dans cette catégorie</p>
                            <p class="font-bold text-gray-800 text-lg">{{ $category->products->count() }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Date de création</p>
                            <p class="font-bold text-gray-800 text-lg">
                                @if($category->created_at)
                                    {{ $category->created_at->format('d/m/Y') }}
                                @else
                                    Non définie
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 pt-6 border-t border-gray-200">
                    <div class="flex gap-3">
                        <a href="{{ route('categories.show', $category->id) }}" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="bi bi-eye"></i>
                            <span>Voir la catégorie</span>
                        </a>
                        
                        <a href="{{ route('categories.index') }}" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="bi bi-x-circle"></i>
                            <span>Annuler</span>
                        </a>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="reset" 
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="bi bi-arrow-clockwise"></i>
                            <span>Réinitialiser</span>
                        </button>
                        
                        <button type="submit" 
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 group">
                            <i class="bi bi-save text-xl group-hover:rotate-12 transition-transform duration-300"></i>
                            <span>Enregistrer les modifications</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compteur de caractères pour la description
    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    
    function updateCharCount() {
        const count = description.value.length;
        charCount.textContent = count + '/500 caractères';
        
        if (count > 500) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-400');
        }
    }
    
    description.addEventListener('input', updateCharCount);
    updateCharCount(); // Initialiser
    
    // Validation avant envoi
    document.querySelector('form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        if (!name) {
            alert('❌ Le nom de la catégorie est obligatoire.');
            e.preventDefault();
            return false;
        }
        
        // Petit effet de chargement
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enregistrement en cours...';
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection