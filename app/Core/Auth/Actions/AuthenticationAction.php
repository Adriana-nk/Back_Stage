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

final class AuthenticationAction implements IAuthentification
{
    /**
     * Valide le compte utilisateur avec un code reçu par email
     * @param string $email
     * @param string $validation_code
     * @return array
     */
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
    /**
     * Envoie un email de réinitialisation de mot de passe
     * @param string $email
     * @return array
     */
    public function forgotPassword(string $email): array
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return BaseResponse::validationError('Aucun utilisateur trouvé avec cet email.');
        }
        // Utilise le broker par défaut de Laravel
        $status = \Password::sendResetLink(['email' => $email]);
        if ($status === \Password::RESET_LINK_SENT) {
            return BaseResponse::success('Lien de réinitialisation envoyé à votre adresse email.');
        } else {
            return BaseResponse::serverError('Impossible d\'envoyer le lien de réinitialisation.');
        }
    }

    /**
     * Réinitialise le mot de passe avec le token
     * @param array $data (email, token, password, password_confirmation)
     * @return array
     */
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
        $status = \Password::reset(
            $data,
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
        if ($status === \Password::PASSWORD_RESET) {
            return BaseResponse::success('Mot de passe réinitialisé avec succès.');
        } else {
            return BaseResponse::serverError('Le token est invalide ou expiré.');
        }
    }
    /**
     * Handle user login using the provided LoginDto.
     *
     * @param LoginDto $loginDto
     * @return array
     */
    public function login(LoginDto $loginDto): array
    {
        try {
            // Recherche de l'utilisateur par email
            $user = User::where('email', $loginDto->email)->first();

            if (!$user) {
                return BaseResponse::unauthorized(
                    'Aucun utilisateur trouvé avec cet email.',
                    $loginDto->toArray()
                );
            }

            // Vérification du mot de passe
            if (!Hash::check($loginDto->password, $user->password)) {
                return BaseResponse::unauthorized(
                    'Mot de passe incorrect.',
                    $loginDto->toArray()
                );
            }

            // Connexion de l'utilisateur
            Auth::login($user);

            return BaseResponse::success(
                'Connexion réussie.',
                $user->toArray()
            );
        } catch (\Throwable $th) {
            return BaseResponse::serverError(
                'Une erreur est survenue lors de la tentative de connexion.',
                $loginDto->toArray()
            );
        }
    }

    /**
     * Handle user registration.
     *
     * @param array $data
     * @return array
     */
    public function register(RegisterDto $registerDto): array
    {
        try {
            // Vérifier si l'email existe déjà
            if (User::where('email', $registerDto->email)->exists()) {
                return BaseResponse::validationError('Cet email est déjà utilisé.', ['email' => ['Email déjà pris']]);
            }

            // Créer un nouvel utilisateur
            $user = User::create($registerDto->toArray());

            return BaseResponse::created('Utilisateur créé avec succès.', ['user' => $user->toArray()]);
        } catch (\Exception $e) {
            // Log l'erreur si besoin
            \Log::error('Erreur lors de l\'enregistrement : ' . $e->getMessage());

            return BaseResponse::serverError('Une erreur est survenue lors de l\'enregistrement.');
        }
    }


}
