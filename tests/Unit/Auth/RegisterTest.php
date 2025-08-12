<?php

use App\Core\Auth\Actions\AuthenticationAction;
use App\Core\Auth\Dto\RegisterDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        'genre' => 'M',
        'region' => 'Centre',
        'ville' => 'Yaoundé',
        'profil' => 'Client',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];
    $response = $auth->register($data);

    expect($response['success'])->toBeTrue();
    expect($response['code'])->toBe(201);
    expect($response['message'])->toBe('Utilisateur enregistré avec succès');

    // Vérifier que l'utilisateur existe en base
    $user = User::where('email', 'john.doe@example.com')->first();
    expect($user)->not->toBeNull();
    expect(Hash::check('password123', $user->password))->toBeTrue();
});

