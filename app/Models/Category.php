<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'description',
    ];

    // Relation avec les produits
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Relation parent (catégorie parente)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relation enfants (sous-catégories)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Récupérer tous les produits de la catégorie et de ses sous-catégories
    public function getAllProducts()
    {
        // Récupérer tous les produits de cette catégorie
        $products = $this->products;
        
        // Récupérer récursivement les produits des sous-catégories
        foreach ($this->children as $child) {
            $products = $products->merge($child->getAllProducts());
        }
        
        return $products;
    }

    // Compter tous les produits dans la catégorie et ses sous-catégories
    public function getTotalProductsWithDescendants()
    {
        $total = $this->products->count();
        
        foreach ($this->children as $child) {
            $total += $child->getTotalProductsWithDescendants();
        }
        
        return $total;
    }

    // Vérifier si c'est une catégorie principale
    public function isMainCategory()
    {
        return is_null($this->parent_id);
    }

    // Vérifier si c'est une sous-catégorie
    public function isSubCategory()
    {
        return !is_null($this->parent_id);
    }

    // Récupérer le chemin complet de la catégorie
    public function getFullPath()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' → ', $path);
    }
}