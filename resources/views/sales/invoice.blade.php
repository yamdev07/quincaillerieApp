@extends('layouts.app')

@section('title', 'Facture #' . $sale->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Zone d'impression -->
        <div id="invoice" class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-200 print:shadow-none print:border-0">
            <!-- En-tête de la facture -->
            <div class="flex justify-between items-start mb-8 pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">FACTURE</h1>
                    <p class="text-gray-600">N° {{ $sale->id }}</p>
                    <p class="text-gray-600">Date: {{ $sale->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="text-right">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <span class="text-white text-2xl font-bold">💰</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ config('app.name', 'StockMaster') }}</h2>
                    <p class="text-gray-600">Votre partenaire de confiance</p>
                </div>
            </div>

            <!-- Informations client et vendeur -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Client -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-badge text-blue-600"></i>
                        CLIENT
                    </h3>
                    @if($sale->client)
                        <div class="space-y-2">
                            <p class="font-semibold text-gray-800 text-lg">{{ $sale->client->name }}</p>
                            @if($sale->client->email)
                                <p class="text-gray-600">{{ $sale->client->email }}</p>
                            @endif
                            @if($sale->client->phone)
                                <p class="text-gray-600">{{ $sale->client->phone }}</p>
                            @endif
                            @if($sale->client->address)
                                <p class="text-gray-600">{{ $sale->client->address }}</p>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 italic">Client non spécifié</p>
                    @endif
                </div>

                <!-- Vendeur -->
                <div class="bg-blue-50 p-6 rounded-xl">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="bi bi-shop text-blue-600"></i>
                        VENDEUR
                    </h3>
                    <div class="space-y-2">
                        <p class="font-semibold text-gray-800 text-lg">{{ $sale->user->name }}</p>
                        <p class="text-gray-600">{{ $sale->user->email }}</p>
                        <p class="text-gray-600 capitalize">{{ $sale->user->role }}</p>
                    </div>
                </div>
            </div>

            <!-- Détails des produits -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-cart-check text-blue-600"></i>
                    DÉTAIL DES PRODUITS
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-800 to-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sale->items as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                                {{ substr($item->product->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                                                <p class="text-xs text-gray-500">Ref: {{ $item->product->reference ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-800 font-medium">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-800 font-bold">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-800 font-bold">{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totaux -->
            <div class="bg-gray-50 p-6 rounded-xl mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 mb-3">Récapitulatif</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nombre d'articles:</span>
                                <span class="font-semibold">{{ $totalQuantity }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Articles différents:</span>
                                <span class="font-semibold">{{ $sale->items->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Sous-total:</span>
                                <span>{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>TVA (0%):</span>
                                <span>0 FCFA</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t border-gray-300">
                                <span>TOTAL:</span>
                                <span>{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conditions et remerciements -->
            <div class="border-t border-gray-200 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Conditions de paiement</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Paiement comptant</li>
                            <li>• La facture est payable immédiatement</li>
                            <li>• Aucun escompte pour paiement anticipé</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Remarques</h4>
                        <p class="text-sm text-gray-600">Merci pour votre confiance. Les produits vendus ne sont ni repris ni échangés.</p>
                    </div>
                </div>
                
                <!-- Signatures -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="text-center">
                            <div class="border-b border-gray-300 pb-2 mb-2 w-48 mx-auto"></div>
                            <p class="text-sm text-gray-600">Signature du client</p>
                        </div>
                        <div class="text-center">
                            <div class="border-b border-gray-300 pb-2 mb-2 w-48 mx-auto"></div>
                            <p class="text-sm text-gray-600">Signature du vendeur</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pied de page -->
            <div class="mt-8 pt-6 border-t border-gray-300 text-center">
                <p class="text-sm text-gray-500">
                    {{ config('app.name', 'StockMaster') }} • 
                    {{ config('app.address', '123 Rue Principale, Ville') }} • 
                    {{ config('app.phone', '+225 XX XX XX XX') }} • 
                    {{ config('app.email', 'contact@stockmaster.com') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Facture générée le {{ now()->format('d/m/Y à H:i') }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center print:hidden">
            <button onclick="window.print()" 
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                <i class="bi bi-printer"></i>
                Imprimer la facture
            </button>
            <a href="{{ route('sales.show', $sale->id) }}" 
               class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                Retour aux détails
            </a>
            <button onclick="downloadAsPDF()" 
                    class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                <i class="bi bi-download"></i>
                Télécharger PDF
            </button>
        </div>
    </div>
</div>

<!-- Styles pour l'impression -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice, #invoice * {
        visibility: visible;
    }
    #invoice {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
        border: none;
        padding: 0;
    }
    .print\:hidden {
        display: none !important;
    }
    .bg-gradient-to-br {
        background: white !important;
    }
    .rounded-2xl {
        border-radius: 0 !important;
    }
    .shadow-2xl {
        box-shadow: none !important;
    }
}
</style>

<!-- Script pour le téléchargement PDF (optionnel) -->
<script>
function downloadAsPDF() {
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    
    // Afficher un indicateur de chargement
    button.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Génération PDF...';
    button.disabled = true;
    
    // Utiliser html2pdf.js si disponible
    if (typeof html2pdf !== 'undefined') {
        const element = document.getElementById('invoice');
        const opt = {
            margin:       10,
            filename:     'facture-{{ $sale->id }}-{{ date("Y-m-d") }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        html2pdf().set(opt).from(element).save().then(() => {
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    } else {
        // Fallback: ouvrir la fenêtre d'impression
        window.print();
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }, 1000);
    }
}

// Configuration pour l'impression
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un message avant l'impression
    window.addEventListener('beforeprint', function() {
        document.body.classList.add('printing');
    });
    
    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
});
</script>

<!-- Option: Ajouter html2pdf via CDN -->
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
@endpush
@endsection