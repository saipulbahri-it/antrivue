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
        $a = Service::where('name', 'Perdata')->first();
        $b = Service::where('name', 'Pidana')->first();
        $c = Service::where('name', 'Hukum, Informasi & Pengaduan')->first();
        $d = Service::where('name', 'Umum, Layanan Prioritas')->first();
        $e = Service::where('name', 'E-Court')->first();
        $f = Service::where('name', 'Inzage')->first();

        Counter::insert([
            ['name' => 'COUNTER 1', 'service_id' => $a->id],
            ['name' => 'COUNTER 2', 'service_id' => $b->id],
            ['name' => 'COUNTER 3', 'service_id' => $c->id],
            ['name' => 'COUNTER 4', 'service_id' => $d->id],
            ['name' => 'COUNTER 5', 'service_id' => $e->id],
            ['name' => 'COUNTER 6', 'service_id' => $f->id],
        ]);
    }
}
