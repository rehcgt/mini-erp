<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::active()->get();
        $products = Product::active()->get();
        return view('invoices.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'] ?? null,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft',
                'subtotal' => 0,
                'tax_amount' => 0,
                'total_amount' => 0
            ]);

            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);

                // Verificar stock disponible
                if (!$product->hasStock($itemData['quantity'])) {
                    throw new \Exception("Stock insuficiente para el producto: {$product->name}");
                }

                $item = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount_amount' => $itemData['discount_amount'] ?? 0,
                    'total_amount' => ($itemData['quantity'] * $itemData['unit_price']) - ($itemData['discount_amount'] ?? 0)
                ]);

                // Reducir stock del producto
                $product->reduceStock($itemData['quantity']);
            }

            // Calcular totales de la factura
            $invoice->calculateTotals();

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Factura creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la factura: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'user', 'items.product']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Solo se pueden editar facturas en estado borrador.');
        }

        $customers = Customer::active()->get();
        $products = Product::active()->get();
        $invoice->load('items.product');

        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Solo se pueden editar facturas en estado borrador.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factura actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.index')
                ->with('error', 'No se pueden eliminar facturas pagadas.');
        }

        DB::beginTransaction();

        try {
            // Restaurar stock de los productos
            foreach ($invoice->items as $item) {
                $item->product->increaseStock($item->quantity);
            }

            $invoice->delete();

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Factura eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar la factura: ' . $e->getMessage());
        }
    }

    /**
     * Marcar factura como pagada
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factura marcada como pagada.');
    }

    /**
     * Cancelar factura
     */
    public function cancel(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'No se pueden cancelar facturas pagadas.');
        }

        DB::beginTransaction();

        try {
            // Restaurar stock de los productos
            foreach ($invoice->items as $item) {
                $item->product->increaseStock($item->quantity);
            }

            $invoice->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Factura cancelada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al cancelar la factura: ' . $e->getMessage());
        }
    }
}
