@extends('layouts.app')

@section('title', 'Catégories')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Bouton Retour -->
        <div class="mb-4">
            <button onclick="window.history.back()" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 group">
                <i class="bi bi-arrow-left-circle text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
                <span>Retour</span>
            </button>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-3">
                        <span class="text-4xl">📂</span>
                        Gestion des catégories
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Organisez et gérez vos catégories de produits</p>
                </div>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('categories.create') }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <i class="bi bi-plus-circle text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                        <span>Nouvelle catégorie</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Barre de Recherche -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="relative">
                <div class="flex items-center gap-4">
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input 
                            type="text" 
                            id="searchInput" 
                            placeholder="Rechercher une catégorie par nom, sous-nom ou ID..." 
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm"
                        >
                        <button 
                            id="clearSearch" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <button 
                        id="searchButton"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2"
                    >
                        <i class="bi bi-search"></i>
                        <span>Rechercher</span>
                    </button>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-sm text-gray-500 flex items-center gap-1">
                        <i class="bi bi-info-circle"></i>
                        Recherchez par : nom, sous-nom, ID
                    </span>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total catégories</p>
                        <h3 id="totalCategories" class="text-3xl font-bold mt-1">{{ $categories->count() }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-folder text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Catégories actives</p>
                        <h3 id="activeCategories" class="text-3xl font-bold mt-1">{{ $categories->count() }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-check-circle text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Organisation</p>
                        <h3 class="text-2xl font-bold mt-1" id="organizationStatus">Optimale</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="bi bi-graph-up text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-100 uppercase tracking-wider">Sous-nom</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-100 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="category-row hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group" 
                                data-id="{{ $category->id }}"
                                data-name="{{ strtolower($category->name) }}"
                                data-subname="{{ strtolower($category->sub_name) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm group-hover:bg-blue-100 group-hover:text-blue-700 transition-colors">
                                        {{ $category->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                                            {{ substr($category->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 category-name">{{ $category->name }}</p>
                                            <p class="text-xs text-gray-500">Catégorie #{{ $category->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="category-subname text-gray-600">{{ $category->sub_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        @if(auth()->user()->role === 'admin')
                                            <!-- Bouton Voir -->
                                            <a href="{{ route('categories.show', $category->id) }}" 
                                               class="inline-flex items-center justify-center w-9 h-9 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                                               title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('categories.edit', $category->id) }}" 
                                               class="inline-flex items-center justify-center w-9 h-9 bg-yellow-400 hover:bg-yellow-500 text-gray-800 rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                                               title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Bouton Supprimer -->
                                            <form action="{{ route('categories.destroy', $category->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette catégorie ?')" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-9 h-9 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                                                        title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                <i class="bi bi-eye"></i>
                                                Lecture seule
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="noResultsRow">
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-folder text-5xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune catégorie trouvée</h3>
                                        <p class="text-gray-500 mb-6">Commencez par créer votre première catégorie</p>
                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('categories.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                                <i class="bi bi-plus-circle"></i>
                                                <span>Créer la première catégorie</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Message Aucun résultat -->
        <div id="noResultsMessage" class="hidden bg-white rounded-2xl shadow-xl p-8 text-center border border-gray-100 mt-6">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-search text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun résultat trouvé</h3>
                <p class="text-gray-500 mb-4">Aucune catégorie ne correspond à votre recherche.</p>
                <button id="clearSearchBtn" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-5 py-2.5 rounded-xl font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                    <i class="bi bi-x-circle"></i>
                    <span>Effacer la recherche</span>
                </button>
            </div>
        </div>

        <!-- Pagination -->
        @if(method_exists($categories, 'links'))
            <div id="paginationContainer" class="mt-6 bg-white rounded-xl shadow-md p-4">
                {{ $categories->links() }}
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

.category-row {
    transition: all 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const clearSearch = document.getElementById('clearSearch');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const categoriesTableBody = document.getElementById('categoriesTableBody');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const noResultsRow = document.getElementById('noResultsRow');
    const paginationContainer = document.getElementById('paginationContainer');
    const totalCategoriesElement = document.getElementById('totalCategories');
    const activeCategoriesElement = document.getElementById('activeCategories');
    const organizationStatusElement = document.getElementById('organizationStatus');
    
    // Initialiser les valeurs
    let originalRows = Array.from(document.querySelectorAll('.category-row'));
    let currentRows = [...originalRows];

    // Fonction de recherche
    function performSearch() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        
        if (searchTerm === '') {
            clearSearch.classList.add('hidden');
            showAllRows();
            updateStats();
            return;
        }
        
        clearSearch.classList.remove('hidden');
        
        // Filtrer les lignes
        const filteredRows = originalRows.filter(row => {
            const id = row.getAttribute('data-id') || '';
            const name = row.getAttribute('data-name') || '';
            const subname = row.getAttribute('data-subname') || '';
            
            return id.includes(searchTerm) || 
                   name.includes(searchTerm) || 
                   subname.includes(searchTerm);
        });
        
        currentRows = filteredRows;
        displayFilteredRows(filteredRows);
        updateStats();
    }
    
    // Afficher les lignes filtrées
    function displayFilteredRows(rows) {
        // Masquer toutes les lignes
        originalRows.forEach(row => {
            row.style.display = 'none';
            row.classList.add('animate-fade-out');
        });
        
        // Masquer la ligne "aucun résultat" initiale si elle existe
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
        
        // Masquer la pagination
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }
        
        // Afficher les lignes filtrées
        if (rows.length > 0) {
            rows.forEach(row => {
                row.style.display = '';
                row.classList.remove('animate-fade-out');
                row.classList.add('animate-fade-in');
            });
            noResultsMessage.classList.add('hidden');
        } else {
            noResultsMessage.classList.remove('hidden');
            noResultsMessage.classList.add('animate-fade-in');
        }
    }
    
    // Afficher toutes les lignes
    function showAllRows() {
        originalRows.forEach(row => {
            row.style.display = '';
            row.classList.remove('animate-fade-out');
            row.classList.add('animate-fade-in');
        });
        
        // Réafficher la ligne "aucun résultat" initiale si nécessaire
        if (noResultsRow && originalRows.length === 0) {
            noResultsRow.style.display = '';
        }
        
        // Réafficher la pagination
        if (paginationContainer) {
            paginationContainer.style.display = 'block';
        }
        
        // Masquer le message "aucun résultat"
        noResultsMessage.classList.add('hidden');
    }
    
    // Mettre à jour les statistiques
    function updateStats() {
        let totalCategories = 0;
        
        currentRows.forEach(row => {
            if (row.style.display !== 'none') {
                totalCategories++;
            }
        });
        
        totalCategoriesElement.textContent = totalCategories;
        activeCategoriesElement.textContent = totalCategories;
        
        // Mettre à jour le statut d'organisation
        if (totalCategories === 0) {
            organizationStatusElement.textContent = "Vide";
            organizationStatusElement.className = "text-2xl font-bold mt-1 text-red-600";
        } else if (totalCategories < 5) {
            organizationStatusElement.textContent = "Bonne";
            organizationStatusElement.className = "text-2xl font-bold mt-1 text-yellow-600";
        } else {
            organizationStatusElement.textContent = "Optimale";
            organizationStatusElement.className = "text-2xl font-bold mt-1 text-green-600";
        }
    }
    
    // Événements
    searchButton.addEventListener('click', performSearch);
    
    searchInput.addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            performSearch();
        }
        performSearch(); // Recherche en temps réel
    });
    
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        clearSearch.classList.add('hidden');
        showAllRows();
        updateStats();
        searchInput.focus();
    });
    
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        clearSearch.classList.add('hidden');
        showAllRows();
        updateStats();
        searchInput.focus();
    });
    
    // Recherche initiale si l'input a déjà une valeur (après F5 par exemple)
    if (searchInput.value.trim() !== '') {
        performSearch();
    }
});
</script>
@endsection