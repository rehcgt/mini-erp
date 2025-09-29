<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'document_number',
        'document_type',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relación con facturas
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Scope para clientes activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtener el nombre completo con documento si existe
     */
    public function getFullNameAttribute()
    {
        $name = $this->name;
        if ($this->document_number) {
            $name .= " ({$this->document_number})";
        }
        return $name;
    }
}
