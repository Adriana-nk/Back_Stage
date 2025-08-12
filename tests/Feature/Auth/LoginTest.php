<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('permet à un utilisateur valide de se connecter', function () {
    // Arrange : créer un utilisateur valide
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password123'),
        'is_validated' => true, // ✅ Important si vérifié par le service
    ]);

    // Act : tenter de se connecter
    $response = $this->postJson('/api/auth/login', [
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    // 🐞 DEBUG ici
    dd($response->json());

    // Assert : vérifier que la réponse est correcte
    $response->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'message',
            'payload' => [
                'access_token',
                'token_type',
            ],
        ]);
});

it('échoue si le mot de passe est incorrect', function () {
    // Arrange
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => Hash::make('password123'),
    ]);

    // Act
    $response = $this->postJson('/api/auth/login', [
        'email' => 'jane@example.com',
        'password' => 'wrong_password',
    ]);

    // Assert
    $response->assertStatus(401)
        ->assertJson([
            'code' => 401,
            'message' => 'An error occured', // selon la logique actuelle du service
        ]);
});

it('échoue si l\'email est inconnu', function () {
    // Act
    $response = $this->postJson('/api/auth/login', [
        'email' => 'unknown@example.com',
        'password' => 'whatever',
    ]);

    // Assert
    $response->assertStatus(401)
        ->assertJson([
            'code' => 401,
            'message' => 'An error occured', // pareil ici, car le message est écrasé
        ]);
});

it('échoue si des champs sont manquants', function () {
    $response = $this->postJson('/api/auth/login', []);

    $response->assertStatus(422)
        ->assertJson([
            'code' => 422,
            'message' => 'Some datas are not valid !',
        ]);
});