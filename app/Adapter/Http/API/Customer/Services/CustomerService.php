<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Services;

use App\Adapter\Http\API\Shared\BaseResponse;
use App\Core\Customer\Actions\CustomerActions;
use App\Core\Customer\Dto\AddToCartDto;
use App\Core\Customer\Dto\AddToFavoritesDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class CustomerService
{
    private CustomerActions $actions;

    public function __construct()
    {
        $this->actions = new CustomerActions();
    }

    /**
     * Validation générique
     */
    private function validate(Request $request, array $rules): ?array
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return BaseResponse::unprocess_entity('Invalid data!', $validator->errors()->toArray());
        }

        return null;
    }

    // ---------------------------
    // PANIER
    // ---------------------------
    

    // ---------------------------
    // FAVORIS
    // ---------------------------
    public function addToFavorites(Request $request, bool $is_api = false): array
    {
        try {
            if ($error = $this->validate($request, [
                'user_id'    => 'required|integer|exists:users,id',
                'product_id' => 'required|integer|exists:products,id',
            ])) return $error;

            $payload = AddToFavoritesDto::fromRequest($request);
            $this->actions->addToFavorites($payload);

            $responsePayload = $payload->toArray();
            $responsePayload['favori'] = true; // Toujours true après ajout

            if ($is_api) $responsePayload['message'] = 'Produit ajouté aux favoris (API)';

            return BaseResponse::result($responsePayload, 200, 'Produit ajouté aux favoris avec succès !');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "Erreur serveur: " . $th->getMessage());
        }
    }

    public function removeFromFavorites(Request $request, bool $is_api = false): array
    {
        try {
            if ($error = $this->validate($request, [
                'user_id'    => 'required|integer|exists:users,id',
                'product_id' => 'required|integer|exists:products,id',
            ])) return $error;

            $payload = AddToFavoritesDto::fromRequest($request);
            $this->actions->removeFromFavorites($payload->user_id, $payload->product_id);

            $responsePayload = $payload->toArray();
            $responsePayload['favori'] = false; // Toujours false après suppression

            if ($is_api) $responsePayload['message'] = 'Produit retiré des favoris (API)';

            return BaseResponse::result($responsePayload, 200, 'Produit retiré des favoris avec succès !');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "Erreur serveur: " . $th->getMessage());
        }
    }

    public function getFavorites(int $userId): array
    {
        try {
            $favorites = $this->actions->getFavorites($userId);

            // S'assurer que chaque produit a favori = true
            $favoritesArray = array_map(function($product) {
                $product['favori'] = true;
                return $product;
            }, $favorites);

            return BaseResponse::result($favoritesArray, 200, 'Favoris récupérés avec succès');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "Erreur serveur: " . $th->getMessage());
        }
    }
    public function toggleFavorite(int $userId, int $productId): array
{
    $user = \App\Models\User::find($userId);
    if (!$user) {
        return ['code' => 404, 'message' => 'Utilisateur non trouvé', 'data' => []];
    }

    // Vérifie si déjà favori
    if ($user->favorites()->where('product_id', $productId)->exists()) {
        $user->favorites()->detach($productId);
        return ['code' => 200, 'message' => 'Retiré des favoris', 'data' => []];
    } else {
        $user->favorites()->attach($productId);
        return ['code' => 200, 'message' => 'Ajouté aux favoris', 'data' => []];
    }
}

}
