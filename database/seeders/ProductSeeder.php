<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Dell Inspiron 15',
                'sku' => 'LAPTOP-001',
                'description' => 'Laptop Dell Inspiron 15 con procesador Intel i5',
                'price' => 2500.00,
                'cost' => 2000.00,
                'stock' => 10,
                'min_stock' => 3,
                'category' => 'Electrónicos',
                'unit' => 'pieza',
                'is_active' => true
            ],
            [
                'name' => 'Mouse Inalámbrico Logitech',
                'sku' => 'MOUSE-001',
                'description' => 'Mouse inalámbrico Logitech MX Master 3',
                'price' => 150.00,
                'cost' => 100.00,
                'stock' => 25,
                'min_stock' => 5,
                'category' => 'Accesorios',
                'unit' => 'pieza',
                'is_active' => true
            ],
            [
                'name' => 'Teclado Mecánico RGB',
                'sku' => 'KEYBOARD-001',
                'description' => 'Teclado mecánico gaming con iluminación RGB',
                'price' => 350.00,
                'cost' => 250.00,
                'stock' => 15,
                'min_stock' => 3,
                'category' => 'Accesorios',
                'unit' => 'pieza',
                'is_active' => true
            ],
            [
                'name' => 'Monitor Samsung 24"',
                'sku' => 'MONITOR-001',
                'description' => 'Monitor Samsung 24" Full HD LED',
                'price' => 800.00,
                'cost' => 650.00,
                'stock' => 8,
                'min_stock' => 2,
                'category' => 'Electrónicos',
                'unit' => 'pieza',
                'is_active' => true
            ],
            [
                'name' => 'Cable HDMI 2m',
                'sku' => 'CABLE-001',
                'description' => 'Cable HDMI 2.0 de 2 metros',
                'price' => 25.00,
                'cost' => 15.00,
                'stock' => 50,
                'min_stock' => 10,
                'category' => 'Cables',
                'unit' => 'pieza',
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            \App\Models\Product::firstOrCreate(['sku' => $product['sku']], $product);
        }
    }
}
