<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création d'un utilisateur admin si non existant
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'telephone' => '690000000',
                'genre' => 'Homme', // au lieu de 'M'
                'region' => 'Centre',
                'ville' => 'Yaoundé',
                'profil' => 'admin',
                'password' => Hash::make('password'),
            ]
        );


        // Création d'un utilisateur simple si non existant
        User::firstOrCreate(
            ['email' => 'john.doe@example.com'],
            [
                'nom' => 'Doe',
                'prenom' => 'John',
                'telephone' => '691111111',
                'genre' => 'Homme', // au lieu de 'M'
                'region' => 'Littoral',
                'ville' => 'Douala',
                'profil' => 'acheteur',
                'password' => Hash::make('password'),
            ]
        );

        // Génération de 5 utilisateurs aléatoires avec la factory
        User::factory(5)->create();

        // Appel du seeder des produits
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
