<?php

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateForm = false;
    public $name = '';
    public $price = '';
    public $description = '';

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.products.manage', compact('products'));
    }

    public function createProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create([
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'stock' => 0,
        ]);

        $this->reset(['name', 'price', 'description', 'showCreateForm']);
        session()->flash('message', 'Producto creado exitosamente.');
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        $this->reset(['name', 'price', 'description']);
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensaje de éxito -->
            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Barra de herramientas -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <!-- Buscador -->
                        <div class="flex-1 mr-4">
                            <input
                                type="text"
                                wire:model.live="search"
                                placeholder="Buscar productos..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <!-- Botón crear -->
                        <button
                            wire:click="toggleCreateForm"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            {{ $showCreateForm ? 'Cancelar' : 'Nuevo Producto' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Formulario de creación -->
            @if($showCreateForm)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Crear Nuevo Producto</h3>

                        <form wire:submit="createProduct" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    wire:model="price"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                                <textarea
                                    wire:model="description"
                                    rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                ></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <button
                                type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Guardar Producto
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Lista de productos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Lista de Productos</h3>

                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($products as $product)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $product->description }}</p>
                                    <div class="mt-3 flex justify-between items-center">
                                        <span class="font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No se encontraron productos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
