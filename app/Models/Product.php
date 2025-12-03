<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Les champs qui peuvent être assignés en masse
    protected $fillable = [
        'name',
        'description',
        'sale_price',
        'discount',
        'purchase_price',
        'quantity',      // Quantité totale achetée (historique)
        'stock',         // Stock disponible actuel ← IMPORTANT
        'supplier_id',
        'category_id'
    ];

    // Conversion des types de données
    protected $casts = [
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'quantity' => 'integer',
        'stock' => 'integer',
    ];

    // Accessor pour la quantité vendue (calculée)
    public function getSoldQuantityAttribute()
    {
        return $this->quantity - $this->stock;
    }

    // Accessor pour le statut du stock
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= 5) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    // Accessor pour la valeur du stock
    public function getStockValueAttribute()
    {
        return $this->stock * $this->purchase_price;
    }

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Scopes (filtres)
    
    // Produits en stock
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
    
    // Produits en rupture de stock
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '=', 0);
    }
    
    // Produits en faible stock (moins de 5)
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('stock', '<=', $threshold)->where('stock', '>', 0);
    }
    
    // Produits par fournisseur
    public function scopeBySupplier($query, $supplier_id)
    {
        return $query->where('supplier_id', $supplier_id);
    }
    
    // Produits par catégorie
    public function scopeByCategory($query, $category_id)
    {
        return $query->where('category_id', $category_id);
    }
    
    // Méthode pour vérifier si on peut vendre une quantité
    public function canSell($quantity)
    {
        return $this->stock >= $quantity;
    }
    
    // Méthode pour vendre (déduire du stock)
    public function sell($quantity)
    {
        if (!$this->canSell($quantity)) {
            throw new \Exception("Stock insuffisant. Disponible: {$this->stock}, Demandé: {$quantity}");
        }
        
        $this->decrement('stock', $quantity);
        return $this;
    }
    
    // Méthode pour réapprovisionner (ajouter au stock et à la quantité totale)
    public function restock($quantity, $purchase_price = null)
    {
        $this->increment('stock', $quantity);
        $this->increment('quantity', $quantity);
        
        if ($purchase_price) {
            $this->update(['purchase_price' => $purchase_price]);
        }
        
        return $this;
    }
    
    // Méthode pour ajuster le stock manuellement
    public function adjustStock($newStock, $reason = null)
    {
        // Si le nouveau stock est supérieur à l'ancien, c'est un réapprovisionnement
        if ($newStock > $this->stock) {
            $difference = $newStock - $this->stock;
            $this->increment('quantity', $difference);
        }
        
        $this->update(['stock' => $newStock]);
        
        // Ici vous pourriez logger l'ajustement
        // StockAdjustment::create([...]);
        
        return $this;
    }
    
    // Méthode pour synchroniser quantity avec stock + vendu
    public function syncQuantity()
    {
        // quantity doit toujours être = stock + vendu
        $sold = $this->saleItems()->sum('quantity') ?? 0;
        $this->update(['quantity' => $this->stock + $sold]);
        
        return $this;
    }
}