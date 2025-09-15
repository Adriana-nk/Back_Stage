<?php

declare(strict_types=1);

namespace App\Core\Auth\Actions;

use App\Core\Auth\Interfaces\IAuthentification;
use App\Core\Auth\Response\BaseResponse;
use App\Core\Auth\Dto\LoginDto;
use App\Core\Auth\Dto\RegisterDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

final class AuthenticationAction implements IAuthentification
{
    public function validateAccount(string $email, string $validation_code): array
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return BaseResponse::validationError('Aucun utilisateur trouvé avec cet email.');
        }
        if ($user->is_validated) {
            return BaseResponse::success('Compte déjà validé.');
        }
        if ($user->validation_code !== $validation_code) {
            return BaseResponse::validationError('Code de validation incorrect.');
        }
        $user->is_validated = true;
        $user->validation_code = null;
        $user->save();
        return BaseResponse::success('Compte validé avec succès.');
    }

    public function forgotPassword(string $email): array
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return BaseResponse::validationError('Aucun utilisateur trouvé avec cet email.');
        }

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            return BaseResponse::success('Lien de réinitialisation envoyé à votre adresse email.');
        }

        return BaseResponse::serverError('Impossible d\'envoyer le lien de réinitialisation.');
    }

    public function resetPassword(array $data): array
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return BaseResponse::validationError('Erreur de validation', $validator->errors()->all());
        }

        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return BaseResponse::success('Mot de passe réinitialisé avec succès.');
        }

        return BaseResponse::serverError('Le token est invalide ou expiré.');
    }

    public function login(LoginDto $loginDto): array
    {
        try {
            if (empty($loginDto->email) || empty($loginDto->password)) {
                return BaseResponse::validationError(
                    'Email et mot de passe sont requis.',
                    $loginDto->toArray()
                );
            }

            $user = User::where('email', $loginDto->email)->first();

            if (!$user) {
                return BaseResponse::unauthorized('Aucun utilisateur trouvé avec cet email.', $loginDto->toArray());
            }

            if (!Hash::check($loginDto->password, $user->password)) {
                return BaseResponse::unauthorized('Mot de passe incorrect.', $loginDto->toArray());
            }

            Auth::login($user);

            // Générer un token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            return BaseResponse::success('Connexion réussie.', [
                'user' => $user->toArray(),
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        } catch (\Throwable $th) {
            \Log::error('Login error: ' . $th->getMessage());

            return BaseResponse::serverError('Une erreur est survenue lors de la connexion.', ['error' => $th->getMessage()]);
        }
    }

    public function register(RegisterDto $registerDto): array
    {
        try {
            $validator = Validator::make($registerDto->toArray(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return BaseResponse::validationError('Erreur de validation', $validator->errors()->all());
            }

            $user = User::create([
                'name' => $registerDto->nom,
                'email' => $registerDto->email,
                'password' => Hash::make($registerDto->password),
                'is_validated' => true // ou false si tu veux validation email
            ]);

            // Générer un token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            return BaseResponse::created('Utilisateur créé avec succès.', [
                'user' => $user->toArray(),
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'enregistrement : ' . $e->getMessage());

            return BaseResponse::serverError('Une erreur est survenue lors de l\'enregistrement.');
        }
    }
}
