<?php

namespace App\Adapter\Http\API\Auth\Controllers;

use App\Core\Auth\Actions\AuthenticationAction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthenticationController extends Controller
{
    protected AuthenticationAction $action;

    public function __construct()
    {
        $this->action = new AuthenticationAction();
    }

    public function register(Request $request)
    {
        return response()->json(
            $this->action->register($request->all())
        );
    }

    public function login(Request $request)
{
    $dto = new \App\Core\Auth\Dto\LoginDto($request->email, $request->password);
    $response = $this->action->login($dto);

    // Retourne la réponse JSON avec le bon code HTTP
    return response()->json($response, $response['code'] ?? 200);
}


    public function forgotPassword(Request $request)
    {
        return response()->json(
            $this->action->forgotPassword($request->input('email'))
        );
    }

    public function resetPassword(Request $request)
    {
        return response()->json(
            $this->action->resetPassword($request->all())
        );
    }

    public function validateAccount(Request $request)
    {
        return response()->json(
            $this->action->validateAccount(
                $request->input('email'),
                $request->input('validation_code')
            )
        );
    }
}
