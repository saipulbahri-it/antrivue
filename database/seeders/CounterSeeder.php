<?php
namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Service;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembayaran = Service::where('name', 'Pembayaran')->first();
        $informasi  = Service::where('name', 'Informasi')->first();
        $pengaduan  = Service::where('name', 'Pengaduan')->first();

        Counter::insert([
            ['name' => 'Loket A', 'service_id' => $pembayaran->id],
            ['name' => 'Loket B', 'service_id' => $informasi->id],
            ['name' => 'Loket C', 'service_id' => $pengaduan->id],
        ]);
    }
}
