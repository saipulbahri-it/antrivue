<?php
namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'       => 'Samsul Bahri',
            'email'      => 'admin@gmail.com',
            'password'   => bcrypt('admin'),
            'role'       => 'admin',
            'counter_id' => null,
        ]);

        // Add 6 counter users with unique emails
        for ($i = 1; $i <= 6; $i++) {
            User::factory()->create([
                'name'       => "Counter User $i",
                'email'      => "cs$i@gmail.com",
                'password'   => bcrypt('cs'),
                'role'       => 'counter',
                'counter_id' => $i,
            ]);
        }

        $this->call([
            ServiceSeeder::class,
            CounterSeeder::class,
        ]);
    }
}
