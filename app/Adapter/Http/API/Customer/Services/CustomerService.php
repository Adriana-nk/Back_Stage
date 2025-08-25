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

    public function addToCart(Request $request, bool $is_api = false): array
    {
        try {
            if ($error = $this->validate($request, [
                'user_id'    => 'required|integer|exists:users,id',
                'product_id' => 'required|integer|exists:products,id',
                'quantity'   => 'nullable|integer|min:1',
            ])) return $error;

            $payload = AddToCartDto::fromRequest($request);
            $this->actions->addToCart($payload);

            $responsePayload = $payload->toArray();
            if ($is_api) $responsePayload['message'] = 'Produit ajouté au panier (API)';

            return BaseResponse::result($responsePayload, 200, 'Produit ajouté au panier avec succès !');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "Erreur serveur: " . $th->getMessage());
        }
    }

    public function removeFromCart(Request $request, bool $is_api = false): array
    {
        try {
            if ($error = $this->validate($request, [
                'user_id'    => 'required|integer|exists:users,id',
                'product_id' => 'required|integer|exists:products,id',
            ])) return $error;

            $payload = AddToCartDto::fromRequest($request);
            $this->actions->removeFromCart($payload->user_id, $payload->product_id);

            $responsePayload = $payload->toArray();
            if ($is_api) $responsePayload['message'] = 'Produit retiré du panier (API)';

            return BaseResponse::result($responsePayload, 200, 'Produit retiré du panier avec succès !');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "Erreur serveur: " . $th->getMessage());
        }
    }

    public function updateCartQuantity(Request $request): array
    {
        try {
            if ($error = $this->validate($request, [
                'user_id'    => 'required|integer|exists:users,id',
                'product_id' => 'required|integer|exists:products,id',
                'quantity'   => 'required|integer|min:1',
            ])) return $error;

            $this->actions->updateCartQuantity(
                $request->user_id,
                $request->product_id,
                $request->quantity
            );

            return BaseResponse::result([], 200, 'Quantité mise à jour avec succès !');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "Erreur serveur: " . $th->getMessage());
        }
    }

    public function getCart(int $userId): array
    {
        try {
            $cartItems = $this->actions->getCart($userId);
            return BaseResponse::result($cartItems, 200, 'Panier récupéré avec succès');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "Erreur serveur: " . $th->getMessage());
        }
    }

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
            return BaseResponse::result($favorites, 200, 'Favoris récupérés avec succès');
        } catch (\Throwable $th) {
            return BaseResponse::result([], 500, "Erreur serveur: " . $th->getMessage());
        }
    }
}
