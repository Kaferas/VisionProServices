@extends('template')

@section('title')
    Information de l'article
@endsection

@section('content')
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex align-items-center mb-3">
        <h2 class="h5 me-auto">Détail de l'article</h2>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="productTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Détail</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">Historique</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="update-tab" data-bs-toggle="tab" data-bs-target="#update" type="button" role="tab">Modification</button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="productTabContent">

        <!-- Detail Tab -->
        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row g-3">

                <!-- Product Detail -->
                <div class="col-12 col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            Détail
                        </div>
                        <div class="card-body">
                            <p><strong>CodeBar:</strong> {{ $product->item_codebar }}</p>
                            <p><strong>Désignation:</strong> {{ $product->item_name }}</p>
                            <p><strong>Quantité:</strong> {{ $product->item_quantity }}</p>
                            <p><strong>Catégorie:</strong> {{ $product->category->title }}</p>
                            <p><strong>Unité de Mesure:</strong> {{ $product->unity->title }}</p>
                            <p><strong>Stockable:</strong> {{ $product->item_type == 0 ? 'OUI' : 'NON' }}</p>
                            <p><strong>L'article est à vendre:</strong> {{ $product->item_isSellable == 0 ? 'OUI' : 'NON' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Price & Tax -->
                <div class="col-12 col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            Prix et Taxe
                        </div>
                        <div class="card-body">
                            <p><strong>Prix de vente:</strong> {{ $product->item_sellprice }}</p>
                            <p><strong>Prix de Reviens:</strong> {{ $product->item_corprice }}</p>
                            <p><strong>Prix d'achat:</strong> {{ $product->item_costprice }}</p>
                            <p><strong>TVA:</strong> {{ $product->item_tva }}</p>
                            <p><strong>TC:</strong> {{ $product->item_tc }}</p>
                            <p><strong>PF:</strong> {{ $product->item_pf }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- History Tab -->
        <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, fuga minus voluptatem rerum numquam dolorum iste explicabo laboriosam optio similique incidunt deserunt perspiciatis voluptatibus provident? Reprehenderit, delectus illum. Veritatis, maxime.</p>
        </div>

        <!-- Update Tab -->
        <div class="tab-pane fade" id="update" role="tabpanel" aria-labelledby="update-tab">
            <div class="card mt-3">
                <div class="card-header">
                    Formulaire
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
