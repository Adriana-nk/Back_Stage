<?php

declare(strict_types=1);

namespace App\Core\Customer\Interfaces;

use App\Core\Customer\Dto\AddToCartDto;
use App\Core\Customer\Dto\AddToFavoritesDto;


interface ICustomer
{
    /**
     * Ajoute un produit au panier de l'utilisateur.
     *
     * @param AddToCartDto $addToCartDto Le DTO contenant les informations de l'utilisateur, du produit et de la quantité.
     *@param AddToFavoritesDto $addToFavoritesDto 
     * @return array La réponse contenant le résultat de l'opération d'ajout au panier.
     */
    public function addToCart(AddToCartDto $addToCartDto): array;
    


}
