<?php
namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::insert([
            ['name' => 'Pembayaran'],
            ['name' => 'Informasi'],
            ['name' => 'Pengaduan'],
        ]);
    }
}
