<?php

declare(strict_types=1);

namespace App\Core\Customer\Dto;
use Illuminate\Http\Request;
use App\Core\Shared\Interfaces\IDto;

final readonly class ProductDto implements IDto
{
    public function __construct(
        public string $nom,
        public ?string $categorie = null,
        public ?string $description = null,
        public float $prix = 0,          // renommé
        public int $stock = 0,
        public ?string $image_url = null,
        public bool $favori = false
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            nom: $request['nom'] ?? '',
            categorie: $request['categorie'] ?? null,
            description: $request['description'] ?? null,
            prix: isset($request['prix']) ? (float)$request['prix'] : 0,   // renommé
            stock: isset($request['stock']) ? (int)$request['stock'] : 0,
            image_url: $request['image_url'] ?? null,
            favori: $request['favori'] ?? false
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            nom: $data['nom'] ?? '',
            categorie: $data['categorie'] ?? null,
            description: $data['description'] ?? null,
            prix: isset($data['prix']) ? (float)$data['prix'] : 0,         // renommé
            stock: isset($data['stock']) ? (int)$data['stock'] : 0,
            image_url: $data['image_url'] ?? null,
            favori: $data['favori'] ?? false
        );
    }

    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'categorie' => $this->categorie,
            'description' => $this->description,
            'prix' => $this->prix,          // renommé
            'stock' => $this->stock,
            'image_url' => $this->image_url,
            'favori' => $this->favori,
        ];
    }
}
