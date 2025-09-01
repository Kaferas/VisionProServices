@extends('template')

@section('title', 'Liste des Catégories de Voitures')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Liste des Catégories</h2>

    <div class="d-flex flex-wrap align-items-center mb-3">
        <a href="{{ route('car_categories.create') }}" class="btn btn-primary me-2">Nouvelle Catégorie</a>

        <form class="d-flex flex-wrap ms-auto" action="{{ route('car_categories.index') }}" method="get">
            <input type="text" class="form-control me-2 mb-2" style="width:200px;"
                   name="search" value="{{ old('search',$search) }}" placeholder="Rechercher...">
            <button class="btn btn-primary mb-2"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle module_data_table">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $value)
                    <tr>
                        <td>{{ $value->category_id }}</td>
                        <td>{{ $value->title }}</td>
                        <td>{{ $value->description }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('car_categories.show',['car_category' => $value]) }}"
                                   class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('car_categories.edit',['car_category' => $value]) }}"
                                   class="btn btn-warning btn-sm text-white">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" onclick="handleDelete(this)"
                                        data-href="{{ route('car_categories.destroy',['car_category' => $value]) }}"
                                        class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Pas de données</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $categories->links() }}
    </div>
</div>
@endsection

@section('js_content')
<script>
    function handleDelete(th) {
        var url = $(th).data('href');

        Swal.fire({
            title: "Supprimer cette catégorie ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler',
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
                        }
                    }
                });
            }
        });
    }
</script>
@endsection
