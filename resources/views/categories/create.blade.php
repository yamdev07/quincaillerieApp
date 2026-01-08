@extends('layouts.app')

@section('title', isset($category) ? 'Modifier la catégorie' : 'Nouvelle catégorie')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Bouton Retour -->
        <div class="mb-4">
            <button onclick="window.history.back()" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 group">
                <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
                <span>Retour</span>
            </button>
        </div>

        <!-- Vérification des autorisations -->
        @if(auth()->user()->role !== 'admin')
            <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl shadow-md animate-fade-in mb-6" role="alert">
                <div class="flex items-center gap-3">
                    <i class="bi bi-shield-exclamation text-2xl text-red-600"></i>
                    <div>
                        <p class="font-semibold">Accès refusé!</p>
                        <p class="text-sm">Vous n'avez pas l'autorisation d'accéder à cette section.</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('categories.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
                        <i class="bi bi-arrow-left"></i>
                        Retour aux catégories
                    </a>
                </div>
            </div>
            @php return; @endphp
        @endif

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                        <span class="text-4xl">{{ isset($category) ? '📝' : '➕' }}</span>
                        {{ isset($category) ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">
                        {{ isset($category) ? 'Mettez à jour les informations de la catégorie' : 'Créez une nouvelle catégorie pour organiser vos produits' }}
                    </p>
                </div>
                <a href="{{ route('categories.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                    <i class="bi bi-folder text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Voir toutes les catégories</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-2xl text-green-600"></i>
                    <div>
                        <p class="font-semibold">Succès!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                <button class="absolute top-4 right-4 text-green-600 hover:text-green-800 transition-colors" onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-2xl text-red-600"></i>
                    <div>
                        <p class="font-semibold">Erreur!</p>
                        <p class="text-sm">Veuillez corriger les erreurs dans le formulaire.</p>
                    </div>
                </div>
                <button class="absolute top-4 right-4 text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        @endif

        <!-- Form Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @endif

                <!-- Informations de la catégorie -->
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-600"></i>
                        Informations de la catégorie
                    </h3>
                    
                    <!-- Nom Field -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nom de la catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-tag text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ $category->name ?? old('name') }}" 
                                required
                                class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm @error('name') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="Ex: Électronique, Mode, Maison..."
                            >
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Sous-nom Field -->
                    <div class="space-y-2">
                        <label for="sub_name" class="block text-sm font-medium text-gray-700">
                            Sous-nom <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-tags text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="sub_name" 
                                name="sub_name" 
                                value="{{ $category->sub_name ?? old('sub_name') }}" 
                                required
                                class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm @error('sub_name') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="Ex: Téléphones, Vêtements, Meubles..."
                            >
                        </div>
                        @error('sub_name')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Exemple d'aperçu -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="bi bi-eye text-blue-600"></i>
                            Aperçu de la catégorie
                        </h4>
                        <div class="flex items-center gap-3">
                            <div id="previewIcon" class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                                {{ isset($category) && $category->name ? substr($category->name, 0, 1) : '?' }}
                            </div>
                            <div>
                                <p id="previewName" class="font-semibold text-gray-800">
                                    {{ $category->name ?? 'Nom de la catégorie' }}
                                </p>
                                <p id="previewSubName" class="text-sm text-gray-600">
                                    {{ $category->sub_name ?? 'Sous-nom' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-100">
                    <button type="submit" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group flex-1">
                        <i class="bi {{ isset($category) ? 'bi-check2-circle' : 'bi-plus-circle' }} text-xl group-hover:scale-110 transition-transform duration-300"></i>
                        <span>{{ isset($category) ? 'Mettre à jour la catégorie' : 'Créer la catégorie' }}</span>
                    </button>
                    
                    <a href="{{ route('categories.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group flex-1">
                        <i class="bi bi-x-circle text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                        <span>Annuler</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Informations supplémentaires -->
        @if(isset($category))
            <div class="mt-6 bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-clock-history text-blue-600"></i>
                    Informations supplémentaires
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-500">Créée le</p>
                        <p class="font-medium text-gray-800">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-500">Dernière modification</p>
                        <p class="font-medium text-gray-800">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-out {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.animate-fade-out {
    animation: fade-out 0.3s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mise à jour de l'aperçu en temps réel
    const nameInput = document.getElementById('name');
    const subNameInput = document.getElementById('sub_name');
    const previewIcon = document.getElementById('previewIcon');
    const previewName = document.getElementById('previewName');
    const previewSubName = document.getElementById('previewSubName');

    function updatePreview() {
        const name = nameInput.value || 'Nom de la catégorie';
        const subName = subNameInput.value || 'Sous-nom';
        
        // Mettre à jour l'icône (première lettre du nom)
        previewIcon.textContent = name.charAt(0).toUpperCase();
        
        // Mettre à jour le nom
        previewName.textContent = name;
        
        // Mettre à jour le sous-nom
        previewSubName.textContent = subName;
    }

    // Écouter les changements dans les champs
    nameInput.addEventListener('input', updatePreview);
    subNameInput.addEventListener('input', updatePreview);

    // Initialiser l'aperçu
    updatePreview();

    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        const subName = subNameInput.value.trim();
        
        if (!name || !subName) {
            e.preventDefault();
            
            // Animation pour les champs vides
            if (!name) {
                nameInput.classList.add('animate-pulse', 'border-red-500');
                setTimeout(() => {
                    nameInput.classList.remove('animate-pulse');
                }, 1000);
            }
            
            if (!subName) {
                subNameInput.classList.add('animate-pulse', 'border-red-500');
                setTimeout(() => {
                    subNameInput.classList.remove('animate-pulse');
                }, 1000);
            }
            
            // Scroll vers le premier champ vide
            const firstEmpty = !name ? nameInput : subNameInput;
            firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstEmpty.focus();
        }
    });
});
</script>
@endsection