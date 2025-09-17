@extends('layouts.admin')

@section('title', 'Modifier un produit')

@section('content')
<div class="container">
    <h1>Modifier le produit : {{ $product->nom }}</h1>
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
      @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $product->nom) }}" required>
        </div>

        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <select class="form-control" id="categorie" name="categorie">
                <option value="Fruits" {{ $product->categorie === 'Fruits' ? 'selected' : '' }}>Fruits</option>
                <option value="Légumes" {{ $product->categorie === 'Légumes' ? 'selected' : '' }}>Légumes</option>
                <option value="Céréales" {{ $product->categorie === 'Céréales' ? 'selected' : '' }}>Céréales</option>
                <option value="Racines" {{ $product->categorie === 'Racines' ? 'selected' : '' }}>Racines</option>
                <option value="Légumineuses" {{ $product->categorie === 'Légumineuses' ? 'selected' : '' }}>Légumineuses</option>
                <option value="" {{ $product->categorie === null ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="prix" class="form-label">Prix</label>
            <input type="number" class="form-control" id="prix" name="prix" value="{{ old('prix', $product->prix) }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
        </div>

         <div class="mb-3">
        <label for="image" class="form-label">Image du produit</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
        
        @if($product->image_url)
            <img id="preview" src="{{ asset('storage/'.$product->image_url) }}" 
                 alt="{{ $product->nom }}" 
                 style="max-height:120px;margin-top:10px;">
        @else
            <img id="preview" src="#" alt="Aperçu" style="display:none;max-height:120px;margin-top:10px;">
        @endif
    </div>


        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="favori" name="favori" value="1" {{ $product->favori ? 'checked' : '' }}>
            <label class="form-check-label" for="favori">Favori</label>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
    <script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = 'block';
}
</script>
</div>
@endsection
