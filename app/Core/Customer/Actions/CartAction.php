<?php

declare(strict_types=1);

namespace App\Core\Customer\Actions;

use App\Models\Cart;
use App\Models\Product;
use App\Core\Customer\Dto\AddToCartDto;
use App\Core\Customer\Response\BaseResponse;
use Illuminate\Support\Facades\Log;

final class CartAction
{
    /**
     * Ajouter un produit au panier
     */
    public function addToCart(AddToCartDto $dto): array
    {
        try {
            $cartItem = Cart::where('user_id', $dto->user_id)
                            ->where('product_id', $dto->product_id)
                            ->first();

            if ($cartItem) {
                $cartItem->quantity += $dto->quantity ?? 1;
                $cartItem->save();
            } else {
                $cartItem = Cart::create([
                    'user_id'    => $dto->user_id,
                    'product_id' => $dto->product_id,
                    'quantity'   => $dto->quantity ?? 1,
                ]);
            }

            return BaseResponse::success('Produit ajouté au panier avec succès.', [
                'cart_item' => $cartItem->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout au panier : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de l\'ajout au panier.');
        }
    }

    /**
     * Retirer un produit du panier
     */
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

            return BaseResponse::success('Produit retiré du panier avec succès.', [
                'product_id' => $productId
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du retrait du panier : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors du retrait du panier.');
        }
    }

    /**
     * Mettre à jour la quantité d'un produit
     */
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
            Log::error('Erreur lors de la mise à jour du panier : ' . $e->getMessage());
            return BaseResponse::serverError('Une erreur est survenue lors de la mise à jour.');
        }
    }

    /**
     * Récupérer le panier d'un utilisateur avec les informations produit
     */
public function getCart(int $userId): array
{
    try {
        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        // Transformer les données pour le frontend
        $cartArray = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'product_name' => $item->product->name ?? '',
                'prix' => $item->product->prix ?? 0,
                'unit' => $item->product->unit ?? '',
                'image' => $item->product->image ?? '',
            ];
        })->toArray();

        return BaseResponse::success('Panier récupéré avec succès.', $cartArray);
    } catch (\Exception $e) {
        Log::error('Erreur lors de la récupération du panier : ' . $e->getMessage());
        return BaseResponse::serverError('Une erreur est survenue lors de la récupération du panier.');
    }
}

}
