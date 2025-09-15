<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un admin par défaut
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'telephone' => '690000000',
            'genre' => 'M',
            'region' => 'Centre',
            'ville' => 'Yaoundé',
            'profil' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // mot de passe: password
        ]);

        // Création d'un utilisateur simple
        User::create([
            'nom' => 'Doe',
            'prenom' => 'John',
            'telephone' => '691111111',
            'genre' => 'M',
            'region' => 'Littoral',
            'ville' => 'Douala',
            'profil' => 'acheteur',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'),
        ]);

        // Tu peux générer plusieurs utilisateurs avec Faker si besoin
        User::factory(5)->create();
    }
}
