<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Controllers;

use App\Adapter\Http\API\Customer\Services\ProductService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProductController extends Controller
{
    /**
     * Récupérer tous les produits
     */
    public function getProducts(Request $request): JsonResponse
    {
        $dto = \App\Core\Customer\Dto\ProductDto::fromRequest($request);
        $res = ProductService::getAllProducts($dto);
        return response()->json($res, $res['code']);
    }

    /**
     * Créer un produit
     */
    public function createProduct(Request $request): JsonResponse
    {
        $res = ProductService::createProduct($request);
        return response()->json($res, $res['code']);
    }

    /**
     * Récupérer un produit spécifique
     */
    public function getProduct(int $id): JsonResponse
    {
        $res = ProductService::getProductById($id);
        return response()->json($res, $res['code']);
    }

    /**
     * Mettre à jour un produit
     */
    public function updateProduct(Request $request, int $id): JsonResponse
    {
        $res = ProductService::updateProduct($id, $request);
        return response()->json($res, $res['code']);
    }

    /**
     * Supprimer un produit
     */
    public function deleteProduct(int $id): JsonResponse
    {
        $res = ProductService::deleteProduct($id);
        return response()->json($res, $res['code']);
    }
}
