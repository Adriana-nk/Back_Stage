<?php

declare(strict_types=1);

namespace App\Core\Customer\Actions;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Favorite;
use App\Core\Customer\Dto\AddToCartDto;
use App\Core\Customer\Response\BaseResponse;
use App\Core\Customer\Interfaces\ICustomer;
use App\Core\Customer\Dto\AddToFavoritesDto;

final class CustomerActions implements ICustomer
{
    public function addToCart(AddToCartDto $addToCartDto): array
    {
        try {
            // Vérifier si le produit existe
            $product = Product::find($addToCartDto->product_id);
            if (!$product) {
                return BaseResponse::validationError('Produit introuvable.', [
                    'product_id' => ['Produit non existant']
                ]);
            }

            // Vérifier si l'utilisateur existe
            $user = User::find($addToCartDto->user_id);
            if (!$user) {
                return BaseResponse::validationError('Utilisateur introuvable.', [
                    'user_id' => ['Utilisateur non existant']
                ]);
            }

            // Vérifier si le produit est déjà dans le panier
            $cartItem = Cart::firstOrCreate(
                ['user_id' => $addToCartDto->user_id, 'product_id' => $addToCartDto->product_id],
                ['quantity' => 0]
            );

            // Incrémenter la quantité
            $cartItem->quantity += $addToCartDto->quantity;
            $cartItem->save();

            return BaseResponse::created('Produit ajouté au panier avec succès.', [
                'cart_item' => $cartItem->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout au panier : ' . $e->getMessage());
            dd(vars: $e);

            return BaseResponse::serverError('Une erreur est survenue lors de l\'ajout au panier.', [
                'exception' => $e->getMessage()
            ]);
        }
    }
    public function addToFavorites(AddToFavoritesDto $dto): array
    {
        try {
            // Vérifier si le produit existe
            $product = Product::find($dto->product_id);
            if (!$product) {
                return BaseResponse::validationError('Produit introuvable.', ['product_id' => ['Produit non existant']]);
            }

            // Vérifier si l'utilisateur existe
            $user = User::find($dto->user_id);
            if (!$user) {
                return BaseResponse::validationError('Utilisateur introuvable.', ['user_id' => ['Utilisateur non existant']]);
            }

            // Vérifier si le produit est déjà dans les favoris
            $favorite = Favorite::where('user_id', $dto->user_id)
                                ->where('product_id', $dto->product_id)
                                ->first();

            if ($favorite) {
                return BaseResponse::success('Produit déjà dans les favoris.', ['favorite' => $favorite->toArray()]);
            }

            // Ajouter le produit aux favoris
            $favorite = Favorite::create($dto->toArray());

            return BaseResponse::created('Produit ajouté aux favoris avec succès.', ['favorite' => $favorite->toArray()]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout aux favoris : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de l\'ajout aux favoris.');
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

}
