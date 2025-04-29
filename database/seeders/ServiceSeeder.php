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
            ['prefix' => 'PE', 'name' => 'Perdata'],
            ['prefix' => 'PI', 'name' => 'Pidana'],
            ['prefix' => 'HI', 'name' => 'Hukum, Informasi & Pengaduan'],
            ['prefix' => 'UL', 'name' => 'Umum, Layanan Prioritas'],
            ['prefix' => 'EC', 'name' => 'E-Court'],
            ['prefix' => 'IZ', 'name' => 'Inzage'],
        ]);
    }
}
