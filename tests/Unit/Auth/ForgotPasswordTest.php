<?php

use App\Core\Auth\Actions\AuthenticationAction;
use App\Core\Auth\Dto\ForgotPasswordDto;
use App\Models\User;
use Illuminate\Support\Facades\Password;

beforeEach(function () {
    User::truncate();
});

it('sends reset link for existing user', function () {
    $user = User::factory()->create([
        'email' => 'reset@example.com',
    ]);

    $dto = new ForgotPasswordDto('reset@example.com');
    $auth = new AuthenticationAction();

    // Mock Password facade
    Password::shouldReceive('sendResetLink')
        ->once()
        ->with(['email' => 'reset@example.com'])
        ->andReturn(Password::RESET_LINK_SENT);

    $response = $auth->forgotPassword($dto->email);
    expect($response['success'])->toBeTrue();
    expect($response['message'])->toContain('Lien de réinitialisation');
});

it('returns error for unknown email', function () {
    $dto = new ForgotPasswordDto('unknown@example.com');
    $auth = new AuthenticationAction();

    $response = $auth->forgotPassword($dto->email);
    expect($response['success'])->toBeFalse();
    expect($response['message'])->toContain('Aucun utilisateur trouvé');
});
