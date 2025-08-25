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
            [
                'nom'         => 'Ordinateur Portable',
                'categorie'   => 'Informatique',
                'description' => 'Un ordinateur performant pour le travail et le jeu.',
                'price'       => 750000,
                'stock'       => 15,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Ordinateur',
                'favori'      => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Smartphone',
                'categorie'   => 'Téléphonie',
                'description' => 'Un smartphone moderne avec un excellent appareil photo.',
                'price'       => 350000,
                'stock'       => 30,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Smartphone',
                'favori'      => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Casque Audio',
                'categorie'   => 'Audio',
                'description' => 'Casque sans fil avec réduction de bruit.',
                'price'       => 95000,
                'stock'       => 50,
                'image_url'   => 'https://via.placeholder.com/300x200.png?text=Casque+Audio',
                'favori'      => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
