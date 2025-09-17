<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Services;

use App\Core\Customer\Actions\CartAction;

final class CartService
{
    private CartAction $cartAction;

    public function __construct()
    {
        $this->cartAction = new CartAction();
    }

    public function addToCart(int $userId, int $productId, int $quantity = 1): array
    {
        return $this->cartAction->addToCart(new \App\Core\Customer\Dto\AddToCartDto([
            'user_id'    => $userId,
            'product_id' => $productId,
            'quantity'   => $quantity,
        ]));
    }

    public function removeFromCart(int $userId, int $productId): array
    {
        return $this->cartAction->removeFromCart($userId, $productId);
    }

    public function updateQuantity(int $userId, int $productId, int $quantity): array
    {
        return $this->cartAction->updateCartQuantity($userId, $productId, $quantity);
    }

    public function getCart(int $userId): array
    {
        return $this->cartAction->getCart($userId);
    }
}
