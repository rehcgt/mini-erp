<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard principal del ERP
     */
    public function index()
    {
        // Métricas del mes actual
        $currentMonth = now()->startOfMonth();

        // Total de ventas del mes
        $monthlyRevenue = Invoice::where('invoice_date', '>=', $currentMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Total de facturas del mes
        $monthlyInvoices = Invoice::where('invoice_date', '>=', $currentMonth)
            ->where('status', '!=', 'cancelled')
            ->count();

        // Facturas pendientes
        $pendingInvoices = Invoice::where('status', 'pending')->count();

        // Productos con stock bajo
        $lowStockProducts = Product::lowStock()->count();

        // Últimas 5 facturas
        $recentInvoices = Invoice::with(['customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Productos más vendidos este mes
        $topProducts = DB::table('invoice_items')
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.invoice_date', '>=', $currentMonth)
            ->where('invoices.status', '!=', 'cancelled')
            ->select(
                'products.name',
                DB::raw('SUM(invoice_items.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Ventas por semana del mes actual
        $weeklySales = Invoice::where('invoice_date', '>=', $currentMonth)
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('WEEK(invoice_date) as week'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy(DB::raw('WEEK(invoice_date)'))
            ->orderBy('week')
            ->get();

        return view('dashboard', compact(
            'monthlyRevenue',
            'monthlyInvoices',
            'pendingInvoices',
            'lowStockProducts',
            'recentInvoices',
            'topProducts',
            'weeklySales'
        ));
    }
}
