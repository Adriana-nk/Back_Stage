<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Controllers;

use App\Adapter\Http\API\Customer\Services\CustomerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class FavoritesController extends Controller
{
    private CustomerService $customerService;

    public function __construct()
    {
        $this->customerService = new CustomerService();
    }

    /**
     * Ajouter ou retirer un produit des favoris (toggle)
     */
    public function toggle(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'code' => 401,
                'message' => 'Utilisateur non authentifié',
                'data' => []
            ], 401);
        }

        $productId = $request->input('product_id');
        if (!$productId) {
            return response()->json([
                'code' => 400,
                'message' => 'ID du produit requis',
                'data' => []
            ], 400);
        }

        // On demande au service de faire le toggle
        $res = $this->customerService->toggleFavorite($user->id, $productId);

        return response()->json($res, $res['code'] ?? 200);
    }

    /**
     * Récupérer les favoris
     */
    public function get(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'code' => 401,
                'message' => 'Utilisateur non authentifié',
                'data' => []
            ], 401);
        }

        $res = $this->customerService->getFavorites($user->id);
        return response()->json($res, $res['code'] ?? 200);
    }
}
