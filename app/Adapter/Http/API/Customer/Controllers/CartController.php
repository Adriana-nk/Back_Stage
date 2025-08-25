<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Controllers;

use App\Adapter\Http\API\Customer\Services\CustomerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CartController extends Controller
{
    private CustomerService $customerService;

    public function __construct()
    {
        $this->customerService = new CustomerService();
    }

    /**
     * Ajouter un produit au panier
     */
    public function add(Request $request): JsonResponse
    {
        $res = $this->customerService->addToCart($request, true);
        return response()->json($res, $res['code'] ?? 200);
    }

    /**
     * Retirer un produit du panier
     */
    public function remove(Request $request): JsonResponse
    {
        $res = $this->customerService->removeFromCart($request, true);
        return response()->json($res, $res['code'] ?? 200);
    }
}
