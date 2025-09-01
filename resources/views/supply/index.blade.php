@extends('template')

@section('title')
    Liste des Approvisionnements
@endsection

@section('content')
<h2 class="mt-4 mb-3">
    Liste des Approvisionnements
</h2>

<div class="row mb-3">
    <div class="col-12 d-flex flex-wrap align-items-center mb-2">
        <a href="{{ route('supply.create') }}" class="btn btn-primary me-2 mb-2">Nouveau</a>

        <div class="dropdown me-2 mb-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-plus"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i> Print</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i> Export to Excel</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i> Export to PDF</a></li>
            </ul>
        </div>

        <div class="ms-auto d-none d-md-block text-muted">Showing 1 to 10 of 150 entries</div>

        <div class="ms-auto me-2 mb-2">
            <form action="{{ route('supply.index') }}" method="get" class="d-flex">
                <input type="text" class="form-control me-2" name="date" value="{{ old('date',$date) }}" placeholder="Select date">
                <select class="form-select me-2" name="status">
                    <option disabled selected>Selectionner le statut</option>
                    <option value="O" {{ old('status',$status) == 0 ? 'selected' : '' }}>En attente</option>
                    <option value="1" {{ old('status',$status) == 1 ? 'selected' : '' }}>Approuvé</option>
                </select>
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <a href="{{ route('supply.index') }}" class="btn btn-secondary mb-2"><i class="bi bi-arrow-clockwise"></i></a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped module_data_table">
        <thead class="table-light">
            <tr>
                <th>Code</th>
                <th>Intitulé</th>
                <th>Statut</th>
                <th>Date Création</th>
                <th>Créer Par</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($supplies as $value)
            <tr>
                <td>{{ $value->supply_code }}</td>
                <td>{{ $value->title }}</td>
                <td>
                    @if ($value->supply_status == 0)
                        <span class="badge bg-warning text-dark">En attente</span>
                    @else
                        <span class="badge bg-success">Approuvé</span>
                    @endif
                </td>
                <td>{{ $value->created_at }}</td>
                <td>{{ $value->user->name }}</td>
                <td>
                    <div class="d-flex justify-content-around">
                        <a href="{{ route('supply.show',['supply' => $value]) }}" class="btn btn-success btn-sm"><i class="bi bi-eye"></i></a>
                        @if ($value->supply_status == 0)
                            <a href="{{ route('supply.edit',['supply' => $value]) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                            <a data-href="{{ route('supply.destroy',['supply' => $value]) }}" onclick="handleDelete(this)" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                        @endif
                    </div>
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
        cancelButtonText: 'Non, Annuler',
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
