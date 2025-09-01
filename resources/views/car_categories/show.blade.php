@extends('template')

@section('title', "Détails de l'article")

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Détails de l'article</h2>
        <a href="{{ route('product.index') }}" class="btn btn-secondary btn-sm">← Retour</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Informations sur l'article
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h6 class="text-muted">Nom :</h6>
                <p class="fw-semibold">{{ $product->name }}</p>
            </div>

            <div class="mb-3">
                <h6 class="text-muted">Description :</h6>
                <p>{{ $product->description }}</p>
            </div>

            <div class="mb-3">
                <h6 class="text-muted">Prix :</h6>
                <p class="fw-semibold text-success">{{ number_format($product->price, 2) }} Fbu</p>
            </div>

            <div class="mb-3">
                <h6 class="text-muted">Catégorie :</h6>
                <p>{{ $product->category->name ?? '—' }}</p>
            </div>

            <div class="mb-3">
                <h6 class="text-muted">Date de création :</h6>
                <p>{{ $product->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('product.edit', $product) }}" class="btn btn-warning">Modifier</a>
            <form action="{{ route('product.destroy', $product) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet article ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection
