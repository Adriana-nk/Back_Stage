<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Services;

use App\Core\Customer\Actions\CartAction;
use App\Core\Customer\Response\BaseResponse;

final class CartService
{
    private CartAction $cartAction;

    public function __construct()
    {
        $this->cartAction = new CartAction();
    }

    /**
     * Ajouter un produit au panier
     */
    public function addToCart(int $userId, int $productId, int $quantity = 1): array
    {
        try {
            // Vérifier si le produit est déjà dans le panier
            $cartItem = \App\Models\Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // Si déjà présent, on incrémente
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // Sinon on crée une entrée
                \App\Models\Cart::create([
                    'user_id'    => $userId,
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                ]);
            }

            return BaseResponse::success('Produit ajouté au panier avec succès');
        } catch (\Throwable $e) {
            \Log::error('Erreur addToCart : ' . $e->getMessage());
            return BaseResponse::serverError('Impossible d’ajouter au panier');
        }
    }

    /**
     * Retirer un produit du panier
     */
    public function removeFromCart(int $userId, int $productId): array
    {
        try {
            $cartItem = \App\Models\Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if (!$cartItem) {
                return BaseResponse::validationError('Produit non trouvé dans le panier');
            }

            $cartItem->delete();

            return BaseResponse::success('Produit retiré du panier');
        } catch (\Throwable $e) {
            \Log::error('Erreur removeFromCart : ' . $e->getMessage());
            return BaseResponse::serverError('Impossible de retirer le produit du panier');
        }
    }

    /**
     * Mettre à jour la quantité d'un produit dans le panier
     */
    public function updateQuantity(int $userId, int $productId, int $quantity): array
    {
        return $this->cartAction->updateCartQuantity($userId, $productId, $quantity);
    }

    /**
     * Récupérer le panier complet d'un utilisateur
     */
   public function getCart(int $userId): array
{
    try {
        $cartItems = $this->cartAction->getCart($userId); // retourne un tableau d'items
        return [
            'code' => 200,
            'message' => 'Panier récupéré avec succès',
            'data' => $cartItems
        ];
    } catch (\Throwable $e) {
        \Log::error('Erreur getCart : ' . $e->getMessage());
        return [
            'code' => 500,
            'message' => 'Une erreur est survenue lors de la récupération du panier.',
            'data' => []
        ];
    }
}

}
