<!-- products/partials/modals.blade.php -->

<!-- Modal de réapprovisionnement -->
<div id="restockModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="restockForm" method="POST" action="{{ route('products.restock', $product->id) }}">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-plus-circle text-green-600 dark:text-green-300"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Réapprovisionner le stock
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Ajouter des unités au stock de <strong>{{ $product->name }}</strong>
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Stock actuel: <span class="font-bold">{{ $product->stock }}</span> unités
                                </p>
                            </div>
                            
                            <div class="mt-4 space-y-4">
                                <!-- Quantité à ajouter -->
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Quantité à ajouter *
                                    </label>
                                    <input type="number" name="amount" id="amount" 
                                           min="1" max="9999" required
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                           placeholder="Ex: 100">
                                </div>
                                
                                <!-- Prix d'achat (optionnel) -->
                                <div>
                                    <label for="purchase_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Prix d'achat unitaire (CFA)
                                    </label>
                                    <input type="number" name="purchase_price" id="purchase_price" 
                                           step="0.01" min="0"
                                           value="{{ $product->purchase_price }}"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                </div>
                                
                                <!-- Prix de vente (optionnel) -->
                                <div>
                                    <label for="sale_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Prix de vente (CFA)
                                    </label>
                                    <input type="number" name="sale_price" id="sale_price" 
                                           step="0.01" min="0"
                                           value="{{ $product->sale_price }}"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                </div>
                                
                                <!-- Motif (optionnel) -->
                                <div>
                                    <label for="motif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Motif
                                    </label>
                                    <textarea name="motif" id="motif" rows="2"
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                              placeholder="Motif du réapprovisionnement..."></textarea>
                                </div>
                                
                                <!-- Document de référence (optionnel) -->
                                <div>
                                    <label for="reference_document" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Document de référence
                                    </label>
                                    <input type="text" name="reference_document" id="reference_document" 
                                           placeholder="Ex: Facture #1234"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                </div>
                                
                                <!-- Fournisseur (optionnel) -->
                                @if($product->supplier_id)
                                <div>
                                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Fournisseur
                                    </label>
                                    <input type="text" 
                                           value="{{ $product->supplier->name ?? '' }}"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 bg-gray-100 dark:bg-gray-900 text-gray-500 dark:text-gray-400 sm:text-sm"
                                           disabled>
                                    <input type="hidden" name="supplier_id" value="{{ $product->supplier_id }}">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-check mr-2"></i>
                        Confirmer l'ajout
                    </button>
                    <button type="button" onclick="closeRestockModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'ajustement de stock -->
<div id="adjustmentModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="adjustmentForm" method="POST" action="{{ route('products.adjust-stock', $product->id) }}">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-sliders-h text-blue-600 dark:text-blue-300"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Ajuster le stock
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Ajustement manuel du stock pour <strong>{{ $product->name }}</strong>
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Stock actuel: <span class="font-bold">{{ $product->stock }}</span> unités
                                </p>
                            </div>
                            
                            <div class="mt-4 space-y-4">
                                <!-- Type d'ajustement -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Type d'ajustement *
                                    </label>
                                    <div class="space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="adjustment_type" value="add" checked 
                                                   class="form-radio text-blue-600 dark:text-blue-400">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Ajouter du stock (+)</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="adjustment_type" value="remove"
                                                   class="form-radio text-red-600 dark:text-red-400">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Retirer du stock (-)</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="adjustment_type" value="set"
                                                   class="form-radio text-purple-600 dark:text-purple-400">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Définir une nouvelle valeur</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Quantité -->
                                <div>
                                    <label for="adjustment_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Quantité *
                                    </label>
                                    <input type="number" name="amount" id="adjustment_amount" 
                                           min="0" max="9999" required
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                           placeholder="Pour 'Définir', entrez la nouvelle valeur totale">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <span id="adjustmentHint"></span>
                                    </p>
                                </div>
                                
                                <!-- Raison -->
                                <div>
                                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Raison de l'ajustement *
                                    </label>
                                    <textarea name="reason" id="reason" rows="2" required
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                              placeholder="Ex: Correction d'inventaire, perte, etc."></textarea>
                                </div>
                                
                                <!-- Prix d'achat (optionnel) -->
                                <div>
                                    <label for="adjustment_purchase_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nouveau prix d'achat (optionnel)
                                    </label>
                                    <input type="number" name="purchase_price" id="adjustment_purchase_price" 
                                           step="0.01" min="0"
                                           value="{{ $product->purchase_price }}"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                </div>
                                
                                <!-- Prix de vente (optionnel) -->
                                <div>
                                    <label for="adjustment_sale_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nouveau prix de vente (optionnel)
                                    </label>
                                    <input type="number" name="sale_price" id="adjustment_sale_price" 
                                           step="0.01" min="0"
                                           value="{{ $product->sale_price }}"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                </div>
                                
                                <!-- Référence (optionnel) -->
                                <div>
                                    <label for="reference_document" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Référence
                                    </label>
                                    <input type="text" name="reference_document" id="reference_document" 
                                           placeholder="Ex: INV-2024-001"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-check mr-2"></i>
                        Appliquer l'ajustement
                    </button>
                    <button type="button" onclick="closeAdjustmentModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de vente rapide (supprimé car demandé) -->
<!-- <div id="quickSaleModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 overflow-y-auto">
    ... contenu supprimé ...
</div> -->

<script>
// Gestion des modaux
function openRestockModal() {
    document.getElementById('restockModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeRestockModal() {
    document.getElementById('restockModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openAdjustmentModal() {
    document.getElementById('adjustmentModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    updateAdjustmentHint();
}

function closeAdjustmentModal() {
    document.getElementById('adjustmentModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Mise à jour du hint pour l'ajustement
function updateAdjustmentHint() {
    const type = document.querySelector('input[name="adjustment_type"]:checked').value;
    const currentStock = {{ $product->stock }};
    const hintElement = document.getElementById('adjustmentHint');
    
    switch(type) {
        case 'add':
            hintElement.textContent = 'Quantité à ajouter au stock actuel';
            hintElement.className = 'text-xs text-blue-600 dark:text-blue-400 mt-1';
            break;
        case 'remove':
            hintElement.textContent = `Quantité à retirer (stock actuel: ${currentStock})`;
            hintElement.className = 'text-xs text-red-600 dark:text-red-400 mt-1';
            break;
        case 'set':
            hintElement.textContent = `Nouvelle valeur totale du stock (actuel: ${currentStock})`;
            hintElement.className = 'text-xs text-purple-600 dark:text-purple-400 mt-1';
            break;
    }
}

// Écouteurs d'événements pour l'ajustement
document.addEventListener('DOMContentLoaded', function() {
    // Mettre à jour le hint quand le type change
    const adjustmentRadios = document.querySelectorAll('input[name="adjustment_type"]');
    adjustmentRadios.forEach(radio => {
        radio.addEventListener('change', updateAdjustmentHint);
    });
    
    // Validation pour la suppression
    const adjustmentForm = document.getElementById('adjustmentForm');
    if (adjustmentForm) {
        adjustmentForm.addEventListener('submit', function(e) {
            const type = document.querySelector('input[name="adjustment_type"]:checked').value;
            const amount = parseInt(document.getElementById('adjustment_amount').value) || 0;
            const currentStock = {{ $product->stock }};
            
            if (type === 'remove' && amount > currentStock) {
                e.preventDefault();
                alert(`Erreur : Vous ne pouvez pas retirer ${amount} unités car le stock actuel est seulement de ${currentStock} unités.`);
                return false;
            }
            
            if (type === 'set' && amount < 0) {
                e.preventDefault();
                alert('Erreur : Le stock ne peut pas être négatif.');
                return false;
            }
            
            return true;
        });
    }
    
    // Validation pour le réapprovisionnement
    const restockForm = document.getElementById('restockForm');
    if (restockForm) {
        restockForm.addEventListener('submit', function(e) {
            const amount = parseInt(document.getElementById('amount').value) || 0;
            
            if (amount <= 0) {
                e.preventDefault();
                alert('Erreur : La quantité doit être supérieure à 0.');
                return false;
            }
            
            if (amount > 99999) {
                e.preventDefault();
                alert('Erreur : La quantité est trop élevée.');
                return false;
            }
            
            return true;
        });
    }
    
    // Fermer les modaux avec la touche Échap
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeRestockModal();
            closeAdjustmentModal();
        }
    });
    
    // Fermer les modaux en cliquant à l'extérieur
    const modals = ['restockModal', 'adjustmentModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    if (modalId === 'restockModal') closeRestockModal();
                    if (modalId === 'adjustmentModal') closeAdjustmentModal();
                }
            });
        }
    });
});
</script>