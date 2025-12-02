<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Corrigez le fillable pour correspondre à votre table
    protected $fillable = [
        'name',
        'description',
        'sale_price',
        'discount',
        'purchase_price',
        'quantity',  // ICI: quantity au lieu de stock
        'supplier_id',
        'category_id'
    ];

    // Accessor pour stock (alias de quantity pour compatibilité)
    public function getStockAttribute()
    {
        return $this->quantity;
    }

    // Mutator pour stock (alias de quantity pour compatibilité)
    public function setStockAttribute($value)
    {
        $this->attributes['quantity'] = $value;
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

    // Scope pour produits en faible stock
    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<=', 5);
    }
}