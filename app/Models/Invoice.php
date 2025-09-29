<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Relación con customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relación con user (quien creó la factura)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con invoice items
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Scope para facturas por estado
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para facturas del mes actual
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereYear('invoice_date', now()->year)
                    ->whereMonth('invoice_date', now()->month);
    }

    /**
     * Generar número de factura automático
     */
    public static function generateInvoiceNumber()
    {
        $year = now()->year;
        $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
        $lastInvoice = self::whereYear('created_at', $year)->count() + 1;
        $sequence = str_pad($lastInvoice, 4, '0', STR_PAD_LEFT);

        return "INV-{$year}{$month}-{$sequence}";
    }

    /**
     * Calcular totales de la factura
     */
    public function calculateTotals()
    {
        $subtotal = $this->items->sum('total_amount');
        $this->subtotal = $subtotal;
        $this->tax_amount = $subtotal * 0.18; // IGV 18%
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->save();
    }
}
