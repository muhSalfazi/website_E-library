<?php

namespace Database\Seeders;

use Database\Factories\PeminjamanFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peminjaman;

class PinjamBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peminjaman ::factory()->count(10)->create();
    }
}