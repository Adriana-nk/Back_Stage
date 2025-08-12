<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Auth\Services;

use App\Adapter\Http\API\shared\BaseResponse;
use App\Adapter\Http\API\Auth\Request\LoginRequest;
use App\Core\Auth\Actions\AuthenticationAction;
use App\Core\Auth\Dto\LoginDto;
use App\Core\Auth\Dto\RegisterDto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class AuthService
{
    public function login(Request $request, bool $is_api = false): array
    {
        try {
            $registerValidator = new LoginRequest();
            $validator = Validator::make($request->all(), $registerValidator->rules());

            if ($validator->fails()) {
                return BaseResponse::unprocess_entity('Some datas are not valid !', $validator->errors()->toArray());
            }

            $payloads = LoginDto::fromRequest($request);
            $email = $payloads->email;
            $auth = new AuthenticationAction();
            $login_user = $auth->login($payloads);

            if ($login_user['code'] === 400) {
                return BaseResponse::result($login_user, $login_user['code'], 'you have not yet validate your account !');
            }

            if ($login_user['code'] === 200) {
                $payloads = [];

                if ($is_api) {
                    $payloads = [
                        'access_token' => User::where('email', $email)->first()->createToken('auth_token')->plainTextToken,
                        'token_type' => 'Bearer',
                    ];
                }

                return BaseResponse::result($payloads, $login_user['code'], 'The user is logging !');
            }

            return BaseResponse::result([], $login_user['code'], $login_user['code'] === 422 ? $login_user['message'] : 'An error occured');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "something went wrong !!");
        }

    }
    public function register(Request $request): array
    {
        // Validation basique des données reçues
      $validator = Validator::make($request->all(), [
    'nom' => 'sometimes|string|max:255',
    'prenom' => 'sometimes|string|max:255',
    'telephone' => 'sometimes|string|max:20',
    'genre' => 'sometimes|in:Homme,Femme',
    'region' => 'sometimes|string|max:255',
    'ville' => 'sometimes|string|max:255',
    'profil' => 'sometimes|string|max:255',
    'email' => 'sometimes|email|unique:users,email',
    'password' => 'sometimes|string|min:6',
]);

if ($validator->fails()) {
    return BaseResponse::unprocess_entity('Some datas are not valid !', $validator->errors()->toArray());
}


        $payloads = RegisterDto::fromRequest($request);
        // Construction du DTO avec les données validées
        $registerDto =  AuthenticationAction::register(  $payloads);    ;

        // Appel de la méthode d'enregistrement (celle fournie précédemment)
        return BaseResponse::result($registerDto, 200, 'The user is registered !');
    }
}
