@extends('layouts.admin')

@section('title', 'Créer un produit')

@section('content')
<div class="container">
    <h1>Créer un nouveau produit</h1>
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
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
        <label for="image" class="form-label">Image du produit</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
        <img id="preview" src="#" alt="Aperçu" style="display:none;max-height:120px;margin-top:10px;">
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="favori" name="favori" value="1">
        <label class="form-check-label" for="favori">Favori</label>
    </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = 'block';
}
</script>

@endsection
 
