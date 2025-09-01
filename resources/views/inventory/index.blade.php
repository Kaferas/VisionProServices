@extends('template')

@section('title')
    Liste des Inventaires
@endsection

@section('content')
<h2 class="h5 mt-4 mb-3">Liste des Inventaires</h2>

<div class="row g-3 mt-3">
    <div class="col-12 d-flex flex-wrap align-items-center mb-2">
        <a href="{{ route('inventory.create') }}" class="btn btn-primary me-2">Nouveau</a>

        <div class="dropdown me-3">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-plus"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i>Export to Excel</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i>Export to PDF</a></li>
            </ul>
        </div>

        <div class="ms-auto d-none d-md-block text-muted">Showing 1 to 10 of 150 entries</div>

        <div class="ms-auto mt-2 mt-sm-0">
            <form action="{{ route('inventory.index') }}" method="get" class="d-flex align-items-center">
                <input type="text" class="form-control me-2" name="date" placeholder="Select date" value="{{ old('date',$date) }}">
                <select class="form-select me-2" name="status">
                    <option disabled selected>Selectionner le statut</option>
                    <option value="0" {{ old('status',$status) == 0 ? 'selected' : '' }}>En attente</option>
                    <option value="1" {{ old('status',$status) == 1 ? 'selected' : '' }}>Approuvé</option>
                </select>
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary ms-2"><i class="bi bi-arrow-clockwise"></i></a>
    </div>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped module_data_table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Intitulé</th>
                        <th>Statut</th>
                        <th>Date Création</th>
                        <th>Créé Par</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventories as $value)
                        <tr>
                            <td>{{ $value->inventory_code }}</td>
                            <td>{{ $value->inventory_title }}</td>
                            <td class="text-center">
                                @if ($value->inventory_status == 0)
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @else
                                    <span class="badge bg-success">Approuvé</span>
                                @endif
                            </td>
                            <td>{{ $value->created_at }}</td>
                            <td>{{ $value->user->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('inventory.show',['inventory' => $value]) }}" class="btn btn-outline-success btn-sm me-1"><i class="bi bi-eye"></i></a>
                                @if ($value->inventory_status == 0)
                                    <a href="{{ route('inventory.edit',['inventory' => $value]) }}" class="btn btn-outline-warning btn-sm me-1"><i class="bi bi-pencil"></i></a>
                                    <a data-href="{{ route('inventory.destroy',['inventory' => $value]) }}" onclick="handleDelete(this)" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Pas de données</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js_content')
<script>
function handleDelete(th) {
    var url = $(th).data('href');

    Swal.fire({
        title: "Etes vous sur de vouloir supprimé les données?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Non,Annuler',
    }).then((result) => {
        if(result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "type" : "principal"
                },
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
                        window.location.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: "error retry !!!",
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
