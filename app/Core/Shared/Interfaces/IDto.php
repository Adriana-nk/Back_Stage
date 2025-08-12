<?php

declare(strict_types=1);

namespace App\Core\Shared\Interfaces;

use Illuminate\Http\Request;

interface IDto
{
    /**
     * Convert DTO to array.
     *
     * @return array
     */
    public function toArray(): array;

    public static function fromRequest(Request $request): self;
}
