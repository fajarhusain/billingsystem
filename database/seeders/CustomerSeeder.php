<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Package;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // daftar dusun + kode
        $dusunMap = [
            'rumasan' => '1',
            'rimalang' => '2',
            'semangeng' => '3',
            'mangonan' => '4',
            'pedoyo' => '5',
        ];

        // pastikan ada paket aktif
        $packageIds = Package::where('status', 'active')->pluck('id')->toArray();
        if (empty($packageIds)) {
            $this->command->error("Tidak ada package aktif, buat dulu di tabel packages.");
            return;
        }

        foreach (range(1, 50) as $i) {
            // pilih dusun random
            $dusun = $faker->randomElement(array_keys($dusunMap));
            $dusunCode = $dusunMap[$dusun];

            // hitung jumlah existing
            $lastCount = Customer::where('dusun', $dusun)->count() + 1;
            $uniqueCode = $dusunCode . str_pad($lastCount, 3, '0', STR_PAD_LEFT);

            Customer::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'dusun' => $dusun,
                'package_id' => $faker->randomElement($packageIds),
                'registration_date' => $faker->dateTimeBetween('-1 years', 'now'),
                'status' => $faker->randomElement(['active', 'suspended', 'terminated']),
                'notes' => $faker->sentence,
                'unique_code' => $uniqueCode,
            ]);
        }
    }
}
