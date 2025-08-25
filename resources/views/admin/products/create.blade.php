@extends('layouts.admin')

@section('title', 'Créer un produit')

@section('content')
<div class="container">
    <h1>Créer un nouveau produit</h1>
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>

        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <select class="form-control" id="categorie" name="categorie">
                <option value="Fruits">Fruits</option>
                <option value="Légumes">Légumes</option>
                <option value="Céréales">Céréales</option>
                <option value="Racines">Racines</option>
                <option value="Légumineuses">Légumineuses</option>
                <option value="">Autre</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="mb-3">
            <label for="prix" class="form-label">Prix</label>
            <input type="number" class="form-control" id="prix" name="prix" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>

        <div class="mb-3">
            <label for="image_url" class="form-label">URL de l'image</label>
            <input type="url" class="form-control" id="image_url" name="image_url">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="favori" name="favori" value="1">
            <label class="form-check-label" for="favori">Favori</label>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection
