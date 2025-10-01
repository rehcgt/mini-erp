<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;

Route::view('/', 'welcome');

// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de Productos
    Route::resource('products', ProductController::class);
    Route::get('products-low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');

    // Gestión de Clientes
    Route::resource('customers', CustomerController::class);

    // Gestión de Facturas
    Route::resource('invoices', InvoiceController::class);
    Route::patch('invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-as-paid');
    Route::patch('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');

    // Reportes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales-by-month', [ReportController::class, 'salesByMonth'])->name('sales-by-month');
        Route::get('/low-stock', [ReportController::class, 'lowStock'])->name('low-stock');
        Route::get('/top-products', [ReportController::class, 'topProducts'])->name('top-products');
        Route::get('/top-customers', [ReportController::class, 'topCustomers'])->name('top-customers');
        Route::get('/business-summary', [ReportController::class, 'businessSummary'])->name('business-summary');
    });
});

// Perfil de usuario
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

// Rutas de Livewire con prefijo /mini-erp
Route::post('/livewire/update', '\Livewire\Mechanisms\HandleRequests\HandleRequests@handleUpdate')
    ->name('livewire.update')
    ->middleware('web');

Route::get('/livewire/livewire.js', '\Livewire\Mechanisms\FrontendAssets\FrontendAssets@returnJavaScriptAsFile')
    ->name('livewire.javascript-assets');

Route::get('/livewire/livewire.js.map', '\Livewire\Mechanisms\FrontendAssets\FrontendAssets@returnSourceMapAsFile')
    ->name('livewire.javascript-source-map');
