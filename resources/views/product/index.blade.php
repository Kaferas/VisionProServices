@extends('template')

@section('title')
    Liste des Articles
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Liste des Articles</h2>

    <div class="d-flex flex-wrap align-items-center mb-3">
        <a href="{{ route('product.create') }}" class="btn btn-primary me-2">Nouveau</a>

        <div class="dropdown me-2">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-plus"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Imprimer</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i>Exporter Excel</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2"></i>Exporter PDF</a></li>
            </ul>
        </div>

        <div class="ms-auto me-2 d-none d-md-block text-muted">
            Showing 1 to 10 of 150 entries
        </div>

        <form class="d-flex flex-wrap" action="{{ route('product.index') }}" method="get">
            <input type="text" class="form-control me-2 mb-2" style="width:200px;"
                   name="search" value="{{ old('search',$search) }}" placeholder="Rechercher...">

            <select class="form-select me-2 mb-2" name="category" style="width:200px;">
                <option disabled selected>Sélectionner la catégorie</option>
                @foreach ($categories as $value)
                    <option value="{{ $value->category_id }}"
                        {{ old('category',$category) == $value->category_id ? 'selected' : '' }}>
                        {{ $value->title }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary mb-2"><i class="bi bi-search"></i></button>
        </form>

        <a href="{{ route('product.index') }}" class="btn btn-outline-secondary ms-2">
            <i class="bi bi-arrow-clockwise"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle module_data_table">
            <thead class="table-dark">
                <tr>
                    <th>Codebar</th>
                    <th>Désignation</th>
                    <th>Catégorie</th>
                    <th>U.M</th>
                    <th>PV</th>
                    <th>Qté</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $value)
                    <tr>
                        <td>{{ $value->item_codebar }}</td>
                        <td>{{ $value->item_name }}</td>
                        <td>{{ $value->category->title }}</td>
                        <td>{{ $value->unity->title }}</td>
                        <td>{{ $value->item_sellprice }}</td>
                        <td>
                            @if ($value->item_type == 0)
                                {{ $value->item_quantity ?? 0 }}
                            @else
                                N/S
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('product.show',['product' => $value]) }}"
                                   class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('product.edit',['product' => $value]) }}"
                                   class="btn btn-warning btn-sm text-white">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" onclick="handleDelete(this)"
                                        data-href="{{ route('product.destroy',['product' => $value]) }}"
                                        class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Pas de données</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js_content')
<script>
    function handleDelete(th) {
        var url = $(th).data('href');

        Swal.fire({
            title: "Etes-vous sûr de vouloir supprimer les données?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non, Annuler',
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: { "_token" : "{{ csrf_token() }}" },
                    success: function(data) {
                        if(data.success) {
                            $('.module_data_table').load(' .module_data_table');
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: data.messages,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "Erreur, réessayez!",
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    }
                });
            }
        });
    }
</script>
@endsection
