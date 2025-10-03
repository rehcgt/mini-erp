<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Error del servidor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-8">
                        <svg class="mx-auto h-24 w-24 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>

                    <h1 class="text-6xl font-bold text-gray-900 dark:text-gray-100 mb-4">500</h1>
                    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Error interno del servidor</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        Ups! Algo salió mal en nuestro servidor. El equipo técnico ha sido notificado.
                    </p>

                    <div class="space-x-4">
                        <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Ir al Dashboard
                        </a>
                        <button onclick="window.location.reload()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Intentar de nuevo
                        </button>
                    </div>

                    @if(app()->environment('local'))
                        <div class="mt-8 text-left">
                            <details class="bg-red-50 dark:bg-red-900/20 p-4 rounded">
                                <summary class="cursor-pointer font-medium text-red-700 dark:text-red-300">
                                    Información de depuración (solo desarrollo)
                                </summary>
                                <pre class="mt-2 text-sm text-red-600 dark:text-red-400 overflow-auto">{{ $exception ?? 'No hay información adicional disponible' }}</pre>
                            </details>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
