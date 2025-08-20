<?php

declare(strict_types=1);

namespace App\Core\Product\Dto;

final readonly class SearchProductDto
{
    public ?string $keyword;
    public ?int $category_id;
    public ?float $min_price;
    public ?float $max_price;

    public function __construct(array $data)
    {
        $this->keyword = $data['keyword'] ?? null;
        $this->category_id = $data['category_id'] ?? null;
        $this->min_price = $data['min_price'] ?? null;
        $this->max_price = $data['max_price'] ?? null;
    }
}
