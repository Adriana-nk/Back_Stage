@extends('layouts.admin')

@section('title', 'Produits')

@section('content')
    <div class="container">
        <h2>Liste des produits</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Ajouter un produit</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Favori</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image_url)
               <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->nom }}" style="height:50px;">

  @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $product->nom }}</td>
                        <td>{{ $product->categorie ?? 'N/A' }}</td>
                        <td>{{ $product->prix }} FCFA</td> <!-- corrigé ici -->
                        <td>{{ $product->favori ? 'Oui' : 'Non' }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf 
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">Aucun produit trouvé</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
@endsection
