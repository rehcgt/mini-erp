<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Dashboard de reportes
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Reporte de ventas por mes
     */
    public function salesByMonth(Request $request)
    {
        $year = $request->get('year', now()->year);

        $salesData = Invoice::whereYear('invoice_date', $year)
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('MONTH(invoice_date) as month'),
                DB::raw('COUNT(*) as total_invoices'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->groupBy(DB::raw('MONTH(invoice_date)'))
            ->orderBy('month')
            ->get();

        // Completar los meses que no tienen ventas
        $monthlyData = collect(range(1, 12))->map(function ($month) use ($salesData) {
            $monthData = $salesData->firstWhere('month', $month);
            return [
                'month' => $month,
                'month_name' => Carbon::create()->month($month)->format('F'),
                'total_invoices' => $monthData->total_invoices ?? 0,
                'total_sales' => $monthData->total_sales ?? 0
            ];
        });

        return view('reports.sales-by-month', compact('monthlyData', 'year'));
    }

    /**
     * Reporte de inventario bajo
     */
    public function lowStock()
    {
        $lowStockProducts = Product::lowStock()->get();

        return view('reports.low-stock', compact('lowStockProducts'));
    }

    /**
     * Reporte de productos más vendidos
     */
    public function topProducts(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $topProducts = DB::table('invoice_items')
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.invoice_date', '>=', $startDate)
            ->where('invoices.invoice_date', '<=', $endDate)
            ->where('invoices.status', '!=', 'cancelled')
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(invoice_items.quantity) as total_sold'),
                DB::raw('SUM(invoice_items.total_amount) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return view('reports.top-products', compact('topProducts', 'startDate', 'endDate'));
    }

    /**
     * Reporte de clientes con más compras
     */
    public function topCustomers(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $topCustomers = Customer::withCount(['invoices' => function ($query) use ($startDate, $endDate) {
                $query->where('invoice_date', '>=', $startDate)
                      ->where('invoice_date', '<=', $endDate)
                      ->where('status', '!=', 'cancelled');
            }])
            ->withSum(['invoices' => function ($query) use ($startDate, $endDate) {
                $query->where('invoice_date', '>=', $startDate)
                      ->where('invoice_date', '<=', $endDate)
                      ->where('status', '!=', 'cancelled');
            }], 'total_amount')
            ->having('invoices_count', '>', 0)
            ->orderBy('invoices_sum_total_amount', 'desc')
            ->limit(10)
            ->get();

        return view('reports.top-customers', compact('topCustomers', 'startDate', 'endDate'));
    }

    /**
     * Resumen general del negocio
     */
    public function businessSummary()
    {
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        // Ventas del mes actual
        $currentMonthSales = Invoice::where('invoice_date', '>=', $currentMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Ventas del mes pasado
        $lastMonthSales = Invoice::where('invoice_date', '>=', $lastMonth)
            ->where('invoice_date', '<', $currentMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Facturas pendientes
        $pendingInvoices = Invoice::where('status', 'pending')->count();

        // Productos con stock bajo
        $lowStockCount = Product::lowStock()->count();

        // Total de clientes activos
        $activeCustomers = Customer::active()->count();

        // Total de productos activos
        $activeProducts = Product::active()->count();

        $summary = [
            'current_month_sales' => $currentMonthSales,
            'last_month_sales' => $lastMonthSales,
            'pending_invoices' => $pendingInvoices,
            'low_stock_products' => $lowStockCount,
            'active_customers' => $activeCustomers,
            'active_products' => $activeProducts
        ];

        return view('reports.business-summary', compact('summary'));
    }
}
