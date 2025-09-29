<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Juan Pérez García',
                'email' => 'juan.perez@email.com',
                'phone' => '+51 999 123 456',
                'document_number' => '12345678',
                'document_type' => 'DNI',
                'address' => 'Av. Javier Prado 123, San Isidro',
                'city' => 'Lima',
                'state' => 'Lima',
                'country' => 'PE',
                'postal_code' => '15036',
                'is_active' => true
            ],
            [
                'name' => 'María González López',
                'email' => 'maria.gonzalez@email.com',
                'phone' => '+51 999 654 321',
                'document_number' => '87654321',
                'document_type' => 'DNI',
                'address' => 'Calle Los Olivos 456, Miraflores',
                'city' => 'Lima',
                'state' => 'Lima',
                'country' => 'PE',
                'postal_code' => '15074',
                'is_active' => true
            ],
            [
                'name' => 'Empresa Tech Solutions SAC',
                'email' => 'ventas@techsolutions.com.pe',
                'phone' => '+51 01 234 5678',
                'document_number' => '20123456789',
                'document_type' => 'RUC',
                'address' => 'Av. El Sol 789, Santiago de Surco',
                'city' => 'Lima',
                'state' => 'Lima',
                'country' => 'PE',
                'postal_code' => '15023',
                'is_active' => true
            ],
            [
                'name' => 'Carlos Rodríguez Vega',
                'email' => 'carlos.rodriguez@email.com',
                'phone' => '+51 999 111 222',
                'document_number' => '11223344',
                'document_type' => 'DNI',
                'address' => 'Jr. Amazonas 321, Pueblo Libre',
                'city' => 'Lima',
                'state' => 'Lima',
                'country' => 'PE',
                'postal_code' => '15084',
                'is_active' => true
            ],
            [
                'name' => 'Ana Silva Morales',
                'email' => 'ana.silva@email.com',
                'phone' => '+51 999 333 444',
                'document_number' => '55667788',
                'document_type' => 'DNI',
                'address' => 'Av. Brasil 654, Jesús María',
                'city' => 'Lima',
                'state' => 'Lima',
                'country' => 'PE',
                'postal_code' => '15072',
                'is_active' => true
            ]
        ];

        foreach ($customers as $customer) {
            \App\Models\Customer::firstOrCreate(['email' => $customer['email']], $customer);
        }
    }
}
