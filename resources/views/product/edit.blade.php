@extends('template')

@section('title')
    Modification d'un article
@endsection

@section('content')
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex align-items-center mb-3">
        <h2 class="h5 me-auto">Modification d'article</h2>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">Formulaire</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('product.update',['product' => $product]) }}" method="POST" class="needs-validation" novalidate>
                        @method('PUT')
                        @csrf

                        @include('product.prodForm')

                        <button type="submit" class="btn btn-primary mt-3">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
