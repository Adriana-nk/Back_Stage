<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Auth\Services;

use App\Core\Auth\Response\BaseResponse;
use App\Adapter\Http\API\Auth\Request\LoginRequest;
use App\Core\Auth\Actions\AuthenticationAction;
use App\Core\Auth\Dto\LoginDto;
use App\Core\Auth\Dto\RegisterDto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

final class AuthService
{
    /**
     * Login utilisateur
     */
public function login(Request $request, bool $is_api = false): array
{
    try {
        \Log::info('=== Début tentative de login ===', [
            'payload' => $request->all()
        ]);

        $validator = Validator::make($request->all(), (new LoginRequest())->rules());

        if ($validator->fails()) {
            \Log::warning('Validation échouée', [
                'erreurs' => $validator->errors()->toArray()
            ]);
            return BaseResponse::validationError(
                'Les données envoyées sont invalides',
                $validator->errors()->toArray()
            );
        }

        $payload = LoginDto::fromRequest($request);

        \Log::info('Données transformées en DTO', [
            'email' => $payload->email,
            'password_clair' => $payload->password,
        ]);

        $user = User::where('email', $payload->email)->first();

        if (!$user) {
            \Log::warning('Utilisateur non trouvé', [
                'email' => $payload->email
            ]);
            return BaseResponse::unauthorized('Aucun utilisateur trouvé avec cet email.');
        }

        \Log::info('Utilisateur trouvé', [
            'id' => $user->id,
            'email' => $user->email,
            'password_hash' => $user->password,
        ]);

        if (!Hash::check($payload->password, $user->password)) {
            \Log::warning('Mot de passe incorrect', [
                'email' => $payload->email,
                'password_clair' => $payload->password
            ]);
            return BaseResponse::unauthorized('Mot de passe incorrect.');
        }

        $data = [];
        if ($is_api) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->toArray(),
            ];

            \Log::info('Token généré avec succès', [
                'user_id' => $user->id,
                'token' => $token
            ]);
        }

        \Log::info('Connexion réussie', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return BaseResponse::success('Connexion réussie.', $data);

    } catch (\Throwable $th) {
        \Log::error('Erreur serveur pendant le login', [
            'message' => $th->getMessage(),
            'trace' => $th->getTraceAsString()
        ]);
        return BaseResponse::serverError(
            'Une erreur est survenue lors de la connexion.',
            ['error' => $th->getMessage()]
        );
    }
}


    /**
     * Registration utilisateur
     */
    public function register(Request $request): array
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'nullable|string|max:20',
                'genre' => 'nullable|in:Homme,Femme',
                'region' => 'nullable|string|max:255',
                'ville' => 'nullable|string|max:255',
                'profil' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
    \Log::info('Validation errors:', $validator->errors()->toArray());
    return BaseResponse::validationError(
        'Certaines données sont invalides',
        $validator->errors()->toArray()
    );
}

            $payload = RegisterDto::fromRequest($request);

            $auth = new AuthenticationAction();
            $auth->register($payload);

            return BaseResponse::created('Utilisateur enregistré avec succès', $payload->toArray());

        } catch (\Throwable $th) {
            return BaseResponse::serverError(
                'Une erreur est survenue lors de l’enregistrement.',
                ['error' => $th->getMessage()]
            );
        }
    }
}
