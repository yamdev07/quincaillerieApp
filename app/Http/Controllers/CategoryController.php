<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // -------------------------
    // ROUTES PUBLIQUES
    // -------------------------

    // Affiche toutes les catégories (lecture seule)
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Affiche une catégorie spécifique (lecture seule)
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    // -------------------------
    // ROUTES ADMIN
    // -------------------------

    // Formulaire pour créer une nouvelle catégorie
    public function create()
    {
        return view('categories.create');
    }

    // Enregistre une nouvelle catégorie
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description ?? null,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    // Formulaire pour éditer une catégorie existante
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    // Met à jour une catégorie existante
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description ?? null,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    // Supprime une catégorie
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}
