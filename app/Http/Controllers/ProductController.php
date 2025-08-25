<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Core\Customer\Response\BaseResponse;
use App\Core\Customer\Dto\ProductDto;

class ProductController extends Controller
{
    // =========================
    // Vues Blade pour l’admin
    // =========================

    public function indexView()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // =========================
    // API JSON pour Angular/Ionic
    // =========================

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('categorie') && $request->categorie !== 'Tout') {
            $query->where('categorie', $request->categorie);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nom', 'like', "%{$search}%");
        }

        $products = $query->get();

        $productsArray = $products->map(function($p) {
            $dto = ProductDto::fromArray($p->toArray());
            return [
                'id' => $p->id,
                'nom' => $dto->nom,
                'categorie' => $dto->categorie ?? 'Autre',
                'description' => $dto->description ?? '',
                'prix' => $dto->prix ?? 0,
                'stock' => $dto->stock ?? 0,
                'favori' => $dto->favori ?? false,
                'image_url' => $dto->image_url ?? 'assets/img/default.png'
            ];
        })->toArray();

        return response()->json(BaseResponse::success("Produits récupérés avec succès", $productsArray));
    }

    public function store(Request $request)
    {
        $dto = ProductDto::fromRequest($request);

        $product = Product::create([
            'nom' => $dto->nom,
            'categorie' => $dto->categorie,
            'description' => $dto->description,
            'prix' => $dto->prix,
            'stock' => $dto->stock,
            'image_url' => $dto->image_url,
            'favori' => $dto->favori ?? false,
        ]);

        return response()->json(BaseResponse::created("Produit ajouté avec succès", $product->toArray()));
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(BaseResponse::validationError("Produit introuvable"));
        }

        return response()->json(BaseResponse::success("Produit récupéré avec succès", $product->toArray()));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(BaseResponse::validationError("Produit introuvable"));
        }

        $dto = ProductDto::fromRequest($request);

        $product->update([
            'nom' => $dto->nom ?? $product->nom,
            'categorie' => $dto->categorie ?? $product->categorie,
            'description' => $dto->description ?? $product->description,
            'prix' => $dto->prix ?? $product->prix,
            'stock' => $dto->stock ?? $product->stock,
            'image_url' => $dto->image_url ?? $product->image_url,
            'favori' => $dto->favori ?? $product->favori,
        ]);

        return response()->json(BaseResponse::success("Produit mis à jour avec succès", $product->toArray()));
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(BaseResponse::validationError("Produit introuvable"));
        }

        $product->delete();

        return response()->json(BaseResponse::success("Produit supprimé avec succès"));
    }
}
