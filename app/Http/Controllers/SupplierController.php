<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')
                         ->with('success', 'Fournisseur ajouté avec succès ✅');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    // ✅ AJOUTÉ : Fonction de mise à jour
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')
                         ->with('success', 'Fournisseur mis à jour avec succès ✅');
    }

    // ✅ AJOUTÉ : Fonction de suppression
    public function destroy(Supplier $supplier)
    {
        // Vérifier si le fournisseur a des produits associés
        if ($supplier->products()->exists()) {
            return redirect()->route('suppliers.index')
                ->with('warning', 'Impossible de supprimer ce fournisseur car des produits lui sont associés.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
                         ->with('success', 'Fournisseur supprimé avec succès ✅');
    }

    // ✅ AJOUTÉ : Fonction pour afficher les produits d'un fournisseur
    public function products(Supplier $supplier)
    {
        $products = $supplier->products()->paginate(10);
        return view('suppliers.products', compact('supplier', 'products'));
    }

    // ✅ AJOUTÉ : Fonction pour afficher les commandes d'un fournisseur
    public function orders(Supplier $supplier)
    {
        // Cette fonction dépend de votre modèle Order
        // $orders = $supplier->orders()->paginate(10);
        // return view('suppliers.orders', compact('supplier', 'orders'));
        
        return redirect()->route('suppliers.show', $supplier)
                         ->with('info', 'Fonctionnalité "Commandes" à implémenter');
    }

    // ✅ AJOUTÉ : Fonction pour les rapports
    public function suppliersReport()
    {
        $suppliers = Supplier::withCount('products')
                            ->orderBy('name')
                            ->get();

        $reportData = [
            'total_suppliers' => $suppliers->count(),
            'suppliers_with_products' => $suppliers->where('products_count', '>', 0)->count(),
            'suppliers_without_products' => $suppliers->where('products_count', 0)->count(),
            'average_products_per_supplier' => $suppliers->avg('products_count'),
        ];

        return view('reports.suppliers', compact('suppliers', 'reportData'));
    }
}