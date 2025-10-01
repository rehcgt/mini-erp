<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar Livewire para usar el prefijo /mini-erp
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/mini-erp/livewire/update', $handle)
                ->middleware('web');
        });

        Livewire::setScriptRoute(function ($handle) {
            return Route::get('/mini-erp/livewire/livewire.js', $handle);
        });

        // Configurar la URL base para los updates de Livewire
        if (config('app.env') !== 'testing') {
            config(['livewire.update_uri' => '/mini-erp/livewire/update']);
        }
    }
}
