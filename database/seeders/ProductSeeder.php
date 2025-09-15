<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            // Légumes traditionnels
            [
                'nom'         => 'Folong',
                'categorie'   => 'Légumes',
                'description' => 'Feuilles de folong fraîches, utilisées pour les plats traditionnels.',
                'prix'        => 500,
                'stock'       => 50,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Folong',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Ndolè',
                'categorie'   => 'Légumes',
                'description' => 'Feuilles de ndolè pour préparer le plat camerounais classique.',
                'prix'        => 600,
                'stock'       => 40,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Ndole',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Okok',
                'categorie'   => 'Légumes',
                'description' => 'Feuilles d\'okok fraîches pour des plats traditionnels du Cameroun.',
                'prix'        => 550,
                'stock'       => 30,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Okok',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Fruits locaux
            [
                'nom'         => 'Mangue',
                'categorie'   => 'Fruits',
                'description' => 'Mangue fraîche et juteuse, cultivée localement.',
                'prix'        => 500,
                'stock'       => 100,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Mangue',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Papaye',
                'categorie'   => 'Fruits',
                'description' => 'Papaye mûre et sucrée du Cameroun.',
                'prix'        => 600,
                'stock'       => 80,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Papaye',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Produits agroalimentaires
            [
                'nom'         => 'Plantain',
                'categorie'   => 'Agroalimentaire',
                'description' => 'Banane plantain fraîche pour cuisiner des plats locaux.',
                'prix'        => 300,
                'stock'       => 150,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Plantain',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Igname',
                'categorie'   => 'Agroalimentaire',
                'description' => 'Igname frais pour préparer des plats traditionnels.',
                'prix'        => 400,
                'stock'       => 120,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Igname',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
