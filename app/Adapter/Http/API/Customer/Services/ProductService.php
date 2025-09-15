<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Services;

use App\Core\Customer\Dto\ProductDto;
use App\Core\Customer\Actions\ProductAction;
use App\Adapter\Http\API\shared\BaseResponse;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

final class ProductService
{
    public static function createProduct(Request $request): array
    {
        try {
            $dto = ProductDto::fromRequest($request);
            $product = ProductAction::createProduct($dto);

            if (($product['code'] ?? null) === 201) {
                return BaseResponse::created('Produit créé avec succès', $product['data'] ?? []);
            }

            return BaseResponse::bad_error($product['message'] ?? 'Erreur lors de la création', $product['data'] ?? []);
        } catch (\Throwable $e) {
            Log::error('Erreur Service::createProduct : ' . $e->getMessage());
            return BaseResponse::error('Une erreur est survenue pendant la création du produit', []);
        }
    }

    public static function updateProduct(int $id, Request $request): array
    {
        try {
            $dto = ProductDto::fromRequest($request);
            $product = ProductAction::updateProduct($id, $dto);

            if (($product['code'] ?? null) === 200) {
                return BaseResponse::success('Produit mis à jour avec succès', $product['data'] ?? []);
            }

            return BaseResponse::bad_error($product['message'] ?? 'Erreur lors de la mise à jour', $product['data'] ?? []);
        } catch (\Throwable $e) {
            Log::error('Erreur Service::updateProduct : ' . $e->getMessage());
            return BaseResponse::error('Une erreur est survenue pendant la mise à jour du produit', []);
        }
    }

    public static function deleteProduct(int $id): array
    {
        try {
            $product = ProductAction::deleteProduct($id);

            if (($product['code'] ?? null) === 200) {
                return BaseResponse::success('Produit supprimé avec succès', []);
            }

            return BaseResponse::bad_error($product['message'] ?? 'Erreur lors de la suppression', $product['data'] ?? []);
        } catch (\Throwable $e) {
            Log::error('Erreur Service::deleteProduct : ' . $e->getMessage());
            return BaseResponse::error('Une erreur est survenue pendant la suppression du produit', []);
        }
    }

    public static function getProductById(int $id): array
    {
        try {
            $product = ProductAction::getProduct($id);

            if (($product['code'] ?? null) === 200) {
                return BaseResponse::success('Produit trouvé', $product['data'] ?? []);
            }

            return BaseResponse::bad_error($product['message'] ?? 'Erreur lors de la récupération', $product['data'] ?? []);
        } catch (\Throwable $e) {
            Log::error('Erreur Service::getProductById : ' . $e->getMessage());
            return BaseResponse::error('Une erreur est survenue pendant la récupération du produit', []);
        }
    }

    public static function getAllProducts(ProductDto $dto): array
    {
        try {
            $products = ProductAction::allProducts($dto);

            if (($products['code'] ?? null) === 200) {
                return BaseResponse::success('Liste des produits', $products['data'] ?? []);
            }

            return BaseResponse::bad_error($products['message'] ?? 'Erreur lors de la récupération', $products['data'] ?? []);
        } catch (\Throwable $e) {
            Log::error('Erreur Service::getAllProducts : ' . $e->getMessage());
            return BaseResponse::error('Une erreur est survenue pendant la récupération des produits', []);
        }
    }
    public static function toggleFavorite(Request $request): array
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return BaseResponse::unauthorized('Utilisateur non authentifié');
            }

            // ✅ Validation de l'input
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer|exists:products,id',
            ]);

            if ($validator->fails()) {
                return BaseResponse::unprocess_entity(
                    'Validation échouée',
                    $validator->errors()->toArray()
                );
            }

            $productId = $request->input('product_id');

            // ✅ Vérifie si le favori existe déjà
            $favorite = Favorite::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($favorite) {
                $favorite->delete();
                return BaseResponse::success('Produit retiré des favoris', []);
            }

            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);

            return BaseResponse::success('Produit ajouté aux favoris', []);

        } catch (\Throwable $e) {
            Log::error('Erreur Service::toggleFavorite : ' . $e->getMessage());
            return BaseResponse::error(
                'Une erreur est survenue lors de la gestion des favoris',
                []
            );
        }
    }

    /**
     * Récupérer tous les favoris de l'utilisateur connecté
     */
    public static function getUserFavoritedProducts(): array
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return BaseResponse::unauthorized('Utilisateur non authentifié');
            }

            // ✅ Récupère les produits liés
            $favorites = Favorite::with('product')
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($fav) {
                    return $fav->product;
                })
                ->filter() // Supprime les favoris sans produit
                ->values();

            return BaseResponse::success('Liste des produits favoris', $favorites->toArray());

        } catch (\Throwable $e) {
            Log::error('Erreur Service::getUserFavoritedProducts : ' . $e->getMessage());
            return BaseResponse::error(
                'Une erreur est survenue lors de la récupération des favoris',
                []
            );
        }
    }

}
