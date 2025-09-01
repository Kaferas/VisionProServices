@extends('template')

@section('title', isset($car_category) ? 'Modifier Catégorie' : 'Nouvelle Catégorie')

@section('content')
<div class="container mt-4">
    <h2>{{ isset($car_category) ? 'Modifier' : 'Nouvelle' }} Catégorie</h2>

    <form method="POST"
          action="{{ isset($car_category)
                        ? route('car_categories.update',$car_category)
                        : route('car_categories.store') }}">
        @csrf
        @if(isset($car_category))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="title" class="form-label">Nom</label>
            <input type="text" name="title" id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $car_category->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $car_category->description ?? '') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('car_categories.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
