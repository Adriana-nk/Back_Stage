<?php

use App\Core\Customer\Actions\CustomerActions;
use App\Core\Customer\Dto\AddToFavoritesDto;
use App\Models\User;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('adds a product to favorites successfully', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $dto = new AddToFavoritesDto([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $customerActions = new CustomerActions();
    $response = $customerActions->addToFavorites($dto);

    // Vérifie que la réponse indique un succès
    expect($response['code'])->toBe(201);
    expect($response['message'])->toBe('Produit ajouté aux favoris avec succès.');

    // Vérifie que l'enregistrement existe bien dans la table favorites
    expect(Favorite::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->exists())->toBeTrue();
});

it('does not duplicate favorite if product is already favorited', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    // Création initiale dans la table favorites
    Favorite::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $dto = new AddToFavoritesDto([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $customerActions = new CustomerActions();
    $response = $customerActions->addToFavorites($dto);

    // Vérifie que la réponse indique succès ou doublon traité
    expect($response['code'])->toBe(200); // Selon ton implémentation
    expect($response['message'])->toBe('Produit déjà dans les favoris.');

    // Vérifie qu'il n'y a qu'un seul enregistrement
    expect(Favorite::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->count())->toBe(1);
});

it('returns validation error if product does not exist', function () {
    $user = User::factory()->create();

    $dto = new AddToFavoritesDto([
        'user_id' => $user->id,
        'product_id' => 9999, // produit inexistant
    ]);

    $customerActions = new CustomerActions();
    $response = $customerActions->addToFavorites($dto);

    expect($response['code'])->toBe(422);
    expect($response['message'])->toBe('Produit introuvable.');
});
