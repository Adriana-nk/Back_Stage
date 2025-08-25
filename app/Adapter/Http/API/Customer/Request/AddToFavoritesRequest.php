<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Customer\Request;

use Illuminate\Foundation\Http\FormRequest;

final class AddToFavoritesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|integer|exists:products,id',
        ];
    }
}
