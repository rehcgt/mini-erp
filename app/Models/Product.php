<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'cost',
        'stock',
        'min_stock',
        'category',
        'unit',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Relación con los ítems de factura
     */
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Scope para productos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para productos con stock bajo
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    /**
     * Verificar si el producto tiene stock suficiente
     */
    public function hasStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    /**
     * Reducir stock del producto
     */
    public function reduceStock($quantity)
    {
        if ($this->hasStock($quantity)) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Aumentar stock del producto
     */
    public function increaseStock($quantity)
    {
        $this->increment('stock', $quantity);
    }
}
