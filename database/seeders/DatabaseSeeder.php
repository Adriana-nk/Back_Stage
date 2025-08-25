<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Exemple : créer un utilisateur de test
        User::factory()->create([
            'nom' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Appeler le seeder des produits
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
