@extends('layouts.app')

@section('title', 'Modifier le Fournisseur')

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
                    <a href="{{ route('suppliers.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
                        <i class="bi bi-arrow-left"></i>
                        Retour aux fournisseurs
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
                        <span class="text-4xl">✏️</span>
                        Modifier le fournisseur
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Mettez à jour les informations du fournisseur #{{ $supplier->id }}</p>
                </div>
                <a href="{{ route('suppliers.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                    <i class="bi bi-truck text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
                    <span>Voir tous les fournisseurs</span>
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
                        <p class="font-semibold">Erreur de validation!</p>
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
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Informations du fournisseur -->
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-info-circle text-orange-600"></i>
                        Informations du fournisseur
                    </h3>
                    
                    <!-- Nom Field -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nom du fournisseur <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-building text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $supplier->name) }}" 
                                required
                                class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 shadow-sm @error('name') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="Nom de l'entreprise..."
                            >
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Contact Field -->
                    <div class="space-y-2">
                        <label for="contact" class="block text-sm font-medium text-gray-700">
                            Personne à contacter
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-person-badge text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="contact" 
                                name="contact" 
                                value="{{ old('contact', $supplier->contact) }}" 
                                class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 shadow-sm @error('contact') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="Nom du responsable..."
                            >
                        </div>
                        @error('contact')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Numéro de téléphone
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-telephone text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', $supplier->phone) }}" 
                                class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 shadow-sm @error('phone') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="+33 1 23 45 67 89"
                            >
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-100">
                    <button type="submit" class="bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group flex-1">
                        <i class="bi bi-check2-circle text-xl group-hover:scale-110 transition-transform duration-300"></i>
                        <span>Mettre à jour le fournisseur</span>
                    </button>
                    
                    <!-- Bouton Supprimer -->
                    <button type="button" onclick="confirmDelete()" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group flex-1">
                        <i class="bi bi-trash text-xl group-hover:scale-110 transition-transform duration-300"></i>
                        <span>Supprimer le fournisseur</span>
                    </button>
                    
                    <a href="{{ route('suppliers.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group flex-1">
                        <i class="bi bi-x-circle text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                        <span>Annuler</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-6 bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="bi bi-clock-history text-orange-600"></i>
                Informations supplémentaires
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <p class="text-sm text-gray-500">Créé le</p>
                    <p class="font-medium text-gray-800">{{ $supplier->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500">Dernière modification</p>
                    <p class="font-medium text-gray-800">{{ $supplier->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500">Produits associés</p>
                    <p class="font-medium text-gray-800">{{ $supplier->products_count ?? $supplier->products()->count() }} produits</p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500">ID du fournisseur</p>
                    <p class="font-medium text-gray-800">#{{ $supplier->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce fournisseur ? Cette action est irréversible.')) {
        // Créer un formulaire de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('suppliers.destroy', $supplier->id) }}';
        
        // Ajouter les champs CSRF et méthode DELETE
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        // Soumettre le formulaire
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection