<?php

declare(strict_types=1);

namespace App\Core\Customer\Dto;

use App\Core\Shared\Interfaces\IDto;
use Illuminate\Http\Request;

final class AddToCartDto implements IDto
{
    public int $user_id;     // 🔥 Ajouté
    public int $product_id;
    public int $quantity;

    public function __construct(array $data)
    {
        $this->user_id    = (int) ($data['user_id'] ?? 0);       // 🔥 Initialisation
        $this->product_id = (int) ($data['product_id'] ?? 0);
        $this->quantity   = (int) ($data['quantity'] ?? 1);
    }

    public function toArray(): array
    {
        return [
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'quantity'   => $this->quantity,
        ];
    }

    public static function fromRequest(Request $request): self
    {
        return new self([
            'user_id'    => $request->user()?->id ?? 0,          // 🔥 On prend l'utilisateur connecté
            'product_id' => $request->input('product_id'),
            'quantity'   => $request->input('quantity', 1),
        ]);
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
