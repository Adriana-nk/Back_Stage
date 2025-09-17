<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Controllers;

use App\Adapter\Http\API\Customer\Services\CartService;
use App\Adapter\Http\API\shared\BaseResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class CartController extends Controller
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function addToCart(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(BaseResponse::unauthorized('Utilisateur non authentifié'), 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $res = $this->cartService->addToCart(
            $user->id,
            $validated['product_id'],
            $validated['quantity'] ?? 1
        );

        return response()->json($res, $res['code']);
    }

    public function removeFromCart(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(BaseResponse::unauthorized('Utilisateur non authentifié'), 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $res = $this->cartService->removeFromCart($user->id, $validated['product_id']);

        return response()->json($res, $res['code']);
    }

    public function updateQuantity(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(BaseResponse::unauthorized('Utilisateur non authentifié'), 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $res = $this->cartService->updateQuantity(
            $user->id,
            $validated['product_id'],
            $validated['quantity']
        );

        return response()->json($res, $res['code']);
    }

    public function getCart(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(BaseResponse::unauthorized('Utilisateur non authentifié'), 401);
        }

        $res = $this->cartService->getCart($user->id);
        return response()->json($res, $res['code']);
    }
}
