<?php

declare(strict_types=1);

namespace App\Core\Customer\Actions;

use App\Core\Customer\Dto\ProductDto;
use App\Core\Customer\Response\BaseResponse;
use App\Models\Product;

final class ProductAction
{
    /**
     * Récupérer tous les produits
     */
    public static function allProducts(ProductDto $productDto): array
    {
        try {
            $query = Product::query();

            if ($productDto->categorie !== null && $productDto->categorie !== 'Tout') {
                $query->where('categorie', $productDto->categorie);
            }

            if ($productDto->nom !== '') {
                $query->where('nom', 'like', '%' . $productDto->nom . '%');
            }

            $products = $query->get()->toArray();

            return BaseResponse::success("Produits récupérés avec succès", $products);

        } catch (\Throwable $th) {
            return BaseResponse::serverError("Erreur lors de la récupération des produits", ['exception' => $th->getMessage()]);
        }
    }

    /**
     * Ajouter un produit
     */
    public static function createProduct(ProductDto $productDto): array
    {
        try {
            $product = Product::create($productDto->toArray());
            return BaseResponse::created("Produit ajouté avec succès", $product->toArray());
        } catch (\Throwable $th) {
            return BaseResponse::serverError("Erreur lors de l'ajout du produit", ['exception' => $th->getMessage()]);
        }
    }

    /**
     * Mettre à jour un produit
     */
    public static function updateProduct(int $id, ProductDto $productDto): array
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return BaseResponse::validationError("Produit introuvable");
            }

            $product->update($productDto->toArray());
            return BaseResponse::success("Produit mis à jour avec succès", $product->toArray());

        } catch (\Throwable $th) {
            return BaseResponse::serverError("Erreur lors de la mise à jour du produit", ['exception' => $th->getMessage()]);
        }
    }

    /**
     * Supprimer un produit
     */
    public static function deleteProduct(int $id): array
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return BaseResponse::validationError("Produit introuvable");
            }

            $product->delete();
            return BaseResponse::success("Produit supprimé avec succès");

        } catch (\Throwable $th) {
            return BaseResponse::serverError("Erreur lors de la suppression du produit", ['exception' => $th->getMessage()]);
        }
    }

    /**
     * Récupérer un produit par ID
     */
    public static function getProduct(int $id): array
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return BaseResponse::validationError("Produit introuvable");
            }

            return BaseResponse::success("Produit récupéré avec succès", $product->toArray());

        } catch (\Throwable $th) {
            return BaseResponse::serverError("Erreur lors de la récupération du produit", ['exception' => $th->getMessage()]);
        }
    }
}
