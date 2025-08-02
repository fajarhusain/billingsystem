<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'name' => 'Basic 10Mbps',
                'speed_mbps' => 10,
                'quota' => 'Unlimited',
                'price' => 150000,
                'description' => 'Paket dasar untuk penggunaan ringan'
            ],
            [
                'name' => 'Standard 25Mbps',
                'speed_mbps' => 25,
                'quota' => 'Unlimited',
                'price' => 250000,
                'description' => 'Paket standar untuk keluarga'
            ],
            [
                'name' => 'Premium 50Mbps',
                'speed_mbps' => 50,
                'quota' => 'Unlimited',
                'price' => 400000,
                'description' => 'Paket premium untuk streaming dan gaming'
            ],
            [
                'name' => 'Ultra 100Mbps',
                'speed_mbps' => 100,
                'quota' => 'Unlimited',
                'price' => 650000,
                'description' => 'Paket ultra untuk bisnis dan kebutuhan berat'
            ]
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}