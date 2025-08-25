<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Request;

use Illuminate\Foundation\Http\FormRequest;

final class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // autorise tous les utilisateurs connectés
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ];
    }
}
