<?php

use App\Core\Auth\Actions\AuthenticationAction;
use App\Core\Auth\Dto\ValidateAccountDto;
use App\Models\User;

beforeEach(function () {
    User::truncate();
});

it('validates account with correct code', function () {
    $user = User::factory()->create([
        'email' => 'valide@example.com',
        'is_validated' => false,
        'validation_code' => '123456',
    ]);

    $dto = new ValidateAccountDto('valide@example.com', '123456');
    $auth = new AuthenticationAction();
    $response = $auth->validateAccount($dto->email, $dto->validation_code);
    expect($response['success'])->toBeTrue();
    expect($response['message'])->toContain('validé');
    $user = $user->fresh();
    expect((bool)$user->is_validated)->toBeTrue();
    expect($user->validation_code)->toBeNull();
});

it('returns error for wrong code', function () {
    $user = User::factory()->create([
        'email' => 'valide2@example.com',
        'is_validated' => false,
        'validation_code' => '654321',
    ]);

    $dto = new ValidateAccountDto('valide2@example.com', '000000');
    $auth = new AuthenticationAction();
    $response = $auth->validateAccount($dto->email, $dto->validation_code);
    expect($response['success'])->toBeFalse();
    expect($response['message'])->toContain('Code de validation incorrect');
});
