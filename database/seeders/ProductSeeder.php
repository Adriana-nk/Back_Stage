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
                'image_url'   => 'img/',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            
            

            // Fruits locaux
            
            

            // Produits agroalimentaires
           
           
        ]);
    }
}
