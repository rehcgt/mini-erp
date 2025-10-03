<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalle del Producto') }}
            </h2>
            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                                {{ $product->name }}
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">SKU</label>
                                    <p class="text-lg text-gray-900 dark:text-gray-100">{{ $product->sku }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Categoría</label>
                                    <p class="text-lg text-gray-900 dark:text-gray-100">{{ $product->category }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Unidad</label>
                                    <p class="text-lg text-gray-900 dark:text-gray-100">{{ $product->unit }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</label>
                                    <p class="text-lg text-gray-900 dark:text-gray-100">{{ $product->description }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="space-y-4">
                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                    <label class="block text-sm font-medium text-green-600 dark:text-green-400">Precio de Venta</label>
                                    <p class="text-3xl font-bold text-green-700 dark:text-green-300">${{ number_format($product->price, 2) }}</p>
                                </div>

                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                    <label class="block text-sm font-medium text-blue-600 dark:text-blue-400">Costo</label>
                                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">${{ number_format($product->cost, 2) }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Stock Actual</label>
                                        <p class="text-2xl font-bold {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $product->stock }}
                                        </p>
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Stock Mínimo</label>
                                        <p class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $product->min_stock }}</p>
                                    </div>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Estado</label>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('products.edit', $product) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>

                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded inline-flex items-center" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                        <p>Creado: {{ $product->created_at->format('d/m/Y H:i') }}</p>
                        <p>Última actualización: {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
