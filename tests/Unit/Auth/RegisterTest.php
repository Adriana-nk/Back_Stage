<?php

use App\Core\Auth\Actions\AuthenticationAction;
use App\Core\Auth\Dto\RegisterDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    // Nettoyer la base avant chaque test
    User::truncate();
});

it('registers a new user successfully', function () {
    $auth = new AuthenticationAction();

    $data = [
        'nom' => 'Doe',
        'prenom' => 'John',
        'telephone' => '0123456789',
        'genre' => 'Homme',
        'region' => 'Centre',
        'ville' => 'Yaoundé',
        'profil' => 'Client',
        'email' => 'john.doe@example.com',
        'password' => 'password123'
    ];
    $payload = new RegisterDto(
        nom: $data['nom'],
        prenom: $data['prenom'],
        telephone: $data['telephone'],
        genre: $data['genre'],
        region: $data['region'],
        ville: $data['ville'],
        profil: $data['profil'],
        email: $data['email'],
        password: $data['password']
    );

    $response = $auth->register($payload);

    expect($response['success'])->toBeTrue();
    expect($response['code'])->toBe(201);

    // Vérifier que l'utilisateur existe en base
    assertDatabaseHas('users', [
        'email' => $payload->email,
        'nom' => $payload->nom,
        'prenom' => $payload->prenom,
        'telephone' => $payload->telephone,
        'genre' => $payload->genre,
        'region' => $payload->region,
        'ville' => $payload->ville,
        'profil' => $payload->profil
        ]
    );
});

