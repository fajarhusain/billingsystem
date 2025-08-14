<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        // Data dummy untuk customers
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St, Anytown, USA',
                'package_id' => 1, // Pastikan ID ini ada di tabel packages
                'registration_date' => Carbon::now()->subDays(10), // 10 hari yang lalu
                'status' => 'active',
                'notes' => 'First customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '0987654321',
                'address' => '456 Elm St, Othertown, USA',
                'package_id' => 2, // Pastikan ID ini ada di tabel packages
                'registration_date' => Carbon::now()->subDays(5), // 5 hari yang lalu
                'status' => 'suspended',
                'notes' => 'Second customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'phone' => '5555555555',
                'address' => '789 Oak St, Sometown, USA',
                'package_id' => 1, // Pastikan ID ini ada di tabel packages
                'registration_date' => Carbon::now()->subDays(1), // 1 hari yang lalu
                'status' => 'terminated',
                'notes' => 'Third customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan lebih banyak data dummy sesuai kebutuhan
        ];

        // Insert data ke tabel customers
        DB::table('customers')->insert($customers);
    }
}