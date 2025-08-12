<?php

use App\Core\Auth\Actions\AuthenticationAction;
use App\Core\Auth\Dto\LoginDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns success response when login is successful', function () {
    $password = 'secret123';

    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make($password),
    ]);

    $loginDto = new LoginDto('user@example.com', $password);

    $auth = new AuthenticationAction();
    $response = $auth->login($loginDto);

    expect($response['code'])->toBe(200);
    expect($response['message'])->toBe('Connexion réussie.');
    expect($response['data']['email'])->toBe($user->email);
});

it('returns unauthorized when user is not found', function () {
    $loginDto = new LoginDto('notfound@example.com', 'any_password');

    $auth = new AuthenticationAction();
    $response = $auth->login($loginDto);

    expect($response['code'])->toBe(401);
    expect($response['message'])->toBe('Aucun utilisateur trouvé avec cet email.');
    expect($response['data'])->toBe($loginDto->toArray());
});

it('returns unauthorized when password is incorrect', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('correct_password'),
    ]);

    $loginDto = new LoginDto('test@example.com', 'wrong_password');

    $auth = new AuthenticationAction();
    $response = $auth->login($loginDto);

    expect($response['code'])->toBe(401);
    expect($response['message'])->toBe('Mot de passe incorrect.');
    expect($response['data'])->toBe($loginDto->toArray());
});
