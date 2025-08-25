<?php

declare(strict_types=1);

namespace App\Core\Customer\Actions;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Favorite;
use App\Core\Customer\Dto\AddToCartDto;
use App\Core\Customer\Dto\AddToFavoritesDto;
use App\Core\Customer\Response\BaseResponse;
use App\Core\Customer\Interfaces\ICustomer;

final class CustomerActions implements ICustomer
{
    // ---------------------------
    // PANIER
    // ---------------------------

    public function addToCart(AddToCartDto $dto): array
    {
        try {
            $product = Product::find($dto->product_id);
            if (!$product) {
                return BaseResponse::validationError('Produit introuvable.', ['product_id' => ['Produit non existant']]);
            }

            $user = User::find($dto->user_id);
            if (!$user) {
                return BaseResponse::validationError('Utilisateur introuvable.', ['user_id' => ['Utilisateur non existant']]);
            }

            $cartItem = Cart::firstOrCreate(
                ['user_id' => $dto->user_id, 'product_id' => $dto->product_id],
                ['quantity' => 0]
            );

            $cartItem->quantity += $dto->quantity;
            $cartItem->save();

            return BaseResponse::created('Produit ajouté au panier avec succès.', [
                'cart_item' => $cartItem->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout au panier : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de l\'ajout au panier.', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function removeFromCart(int $userId, int $productId): array
    {
        try {
            $cartItem = Cart::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

            if (!$cartItem) {
                return BaseResponse::validationError('Produit non trouvé dans le panier.');
            }

            $cartItem->delete();

            return BaseResponse::success('Produit retiré du panier avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du panier : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de la suppression.');
        }
    }

    public function updateCartQuantity(int $userId, int $productId, int $quantity): array
    {
        try {
            $cartItem = Cart::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

            if (!$cartItem) {
                return BaseResponse::validationError('Produit non trouvé dans le panier.');
            }

            $cartItem->quantity = $quantity;
            $cartItem->save();

            return BaseResponse::success('Quantité mise à jour avec succès.', [
                'cart_item' => $cartItem->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du panier : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de la mise à jour.');
        }
    }

    public function getCart(int $userId): array
    {
        try {
            $cartItems = Cart::with('product')->where('user_id', $userId)->get();
            return BaseResponse::success('Panier récupéré avec succès.', $cartItems->toArray());
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération du panier : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de la récupération du panier.');
        }
    }

    // ---------------------------
    // FAVORIS
    // ---------------------------

    public function addToFavorites(AddToFavoritesDto $dto): array
    {
        try {
            $product = Product::find($dto->product_id);
            if (!$product) {
                return BaseResponse::validationError('Produit introuvable.', ['product_id' => ['Produit non existant']]);
            }

            $user = User::find($dto->user_id);
            if (!$user) {
                return BaseResponse::validationError('Utilisateur introuvable.', ['user_id' => ['Utilisateur non existant']]);
            }

            $favorite = Favorite::firstOrCreate(
                ['user_id' => $dto->user_id, 'product_id' => $dto->product_id]
            );

            return BaseResponse::created('Produit ajouté aux favoris avec succès.', [
                'favorite' => $favorite->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout aux favoris : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de l\'ajout aux favoris.');
        }
    }

    public function removeFromFavorites(int $userId, int $productId): array
    {
        try {
            $favorite = Favorite::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();

            if (!$favorite) {
                return BaseResponse::validationError('Produit non trouvé dans les favoris.');
            }

            $favorite->delete();

            return BaseResponse::success('Produit retiré des favoris avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression des favoris : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de la suppression.');
        }
    }

    public function getFavorites(int $userId): array
    {
        try {
            $favorites = Favorite::with('product')->where('user_id', $userId)->get();
            return BaseResponse::success('Favoris récupérés avec succès.', $favorites->toArray());
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des favoris : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de la récupération des favoris.');
        }
    }
}
