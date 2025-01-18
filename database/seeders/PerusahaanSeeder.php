<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use App\Models\Perusahaan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 500) as $index) {
            Perusahaan::create([
                'nama_perusahaan' => $faker->company,
                'alamat' => $faker->address,
                'no_telp' => $faker->phoneNumber,
                'email' => $faker->email,
                'nama_pic' => $faker->name
            ]);
        }
    }
}
