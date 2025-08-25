<?php

declare(strict_types=1);

namespace App\Core\Customer\Dto;

use App\Core\Shared\Interfaces\IDto;
use Illuminate\Http\Request;

final class AddToCartDto implements IDto
{
    public int $user_id;
    public int $product_id;
    public int $quantity;

    public function __construct(array $data)
    {
        $this->user_id = isset($data['user_id']) ? (int) $data['user_id'] : 0;
        $this->product_id = isset($data['product_id']) ? (int) $data['product_id'] : 0;
        $this->quantity = isset($data['quantity']) ? (int) $data['quantity'] : 1;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
        ];
    }

    public static function fromRequest(Request $request): self
    {
        return new self([
            'user_id' => $request->input('user_id'),
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity', 1),
        ]);
    }
}
