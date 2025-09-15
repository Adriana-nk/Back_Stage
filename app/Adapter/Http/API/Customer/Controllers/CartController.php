<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Controllers;

use App\Adapter\Http\API\Customer\Services\CartService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CartController extends Controller
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    /**
     * Ajouter un produit au panier
     */
    public function addToCart(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'code'    => 401,
                'message' => 'Utilisateur non authentifié',
                'data'    => [],
            ], 401);
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

        return response()->json($res, $res['code'] ?? 200);
    }

    /**
     * Retirer un produit du panier
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'code'    => 401,
                'message' => 'Utilisateur non authentifié',
                'data'    => [],
            ], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $res = $this->cartService->removeFromCart(
            $user->id,
            $validated['product_id']
        );

        return response()->json($res, $res['code'] ?? 200);
    }

    /**
     * Mettre à jour la quantité d'un produit dans le panier
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'code'    => 401,
                'message' => 'Utilisateur non authentifié',
                'data'    => [],
            ], 401);
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

        return response()->json($res, $res['code'] ?? 200);
    }

    /**
     * Récupérer tous les produits du panier
     */
public function getCart(Request $request): JsonResponse
{
    $user = $request->user();
    if (!$user) {
        return response()->json([
            'code' => 401,
            'message' => 'Utilisateur non authentifié',
            'data' => []
        ], 401);
    }

    try {
        $res = $this->cartService->getCart($user->id);
        return response()->json([
            'code' => 200,
            'message' => 'Panier récupéré avec succès',
            'data' => $res['data'] ?? []
        ]);
    } catch (\Throwable $e) {
        \Log::error($e->getMessage());
        return response()->json([
            'code' => 500,
            'message' => 'Une erreur est survenue lors de la récupération du panier',
            'data' => []
        ], 500);
    }
}


}
