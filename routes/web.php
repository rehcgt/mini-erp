<?php
use Illuminate\Support\Facades\Route;
// Agregar el prefijo 'mini-erp' a todas las rutas definidas en este grupo ya que el proyecto esta hospedado en una subcarpeta
// Si el proyecto va a estar en la raiz del dominio, eliminar el prefijo
Route::prefix('mini-erp')->group(function () {

    Route::view('/', 'welcome');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    require __DIR__.'/auth.php'; // si auth.php define rutas, también estarán bajo /mini-erp
});
