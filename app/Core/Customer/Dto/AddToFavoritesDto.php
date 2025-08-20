<?php

namespace App\Core\Customer\Dto;

use App\Core\Shared\Interfaces\IDto;
use Illuminate\Http\Request;

final class AddToFavoritesDto implements IDto
{
    public int $user_id;
    public int $product_id;

    public function __construct(array $data)
    {
        $this->user_id = $data['user_id'] ?? 0;
        $this->product_id = $data['product_id'] ?? 0;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'product_id' => $this->product_id
        ];
    }

    public static function fromRequest(Request $request): IDto
    {
        return new self([
            'user_id' => $request->input('user_id'),
            'product_id' => $request->input('product_id'),
        ]);
    }
}
