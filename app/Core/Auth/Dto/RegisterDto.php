<?php

declare(strict_types=1);

namespace App\Core\Auth\Dto;

use App\Core\Shared\Interfaces\IDto;
use Illuminate\Http\Request;

final readonly class RegisterDto implements IDto
{
    public ?string $nom;
    public ?string $prenom;
    public ?string $telephone;
    public ?string $genre;
    public ?string $region;
    public ?string $ville;
    public ?string $profil;
    public ?string $email;
    public ?string $password;

    public function __construct(
        ?string $nom = null,
        ?string $prenom = null,
        ?string $telephone = null,
        ?string $genre = null,
        ?string $region = null,
        ?string $ville = null,
        ?string $profil = null,
        ?string $email = null,
        ?string $password = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->genre = $genre;
        $this->region = $region;
        $this->ville = $ville;
        $this->profil = $profil;
        $this->email = $email;
        $this->password = $password;
    }

    // Crée le DTO depuis une requête Laravel
    public static function fromRequest(Request $request): self
    {
        return new self(
            nom: $request->input('nom'),
            prenom: $request->input('prenom'),
            telephone: $request->input('telephone'),
            genre: $request->input('genre'),
            region: $request->input('region'),
            ville: $request->input('ville'),
            profil: $request->input('profil'),
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }

    public function toArray(): array
    {
        return [
            'nom'       => $this->nom,
            'prenom'    => $this->prenom,
            'telephone' => $this->telephone,
            'genre'     => $this->genre,
            'region'    => $this->region,
            'ville'     => $this->ville,
            'profil'    => $this->profil,
            'email'     => $this->email,
            'password'  => $this->password,
        ];
    }
}
