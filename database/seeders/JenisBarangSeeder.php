<?php

namespace Database\Seeders;

use App\Models\JenisBarang;
use Database\Factories\JenisBarangFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisBarang::factory()->count(10)->create();
    }
}
