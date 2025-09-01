@extends('template')

@section('title')
    Liste des Sortie Stocks
@endsection

@section('content')
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3">
        <h2 class="h5 mb-2 mb-sm-0 me-auto">Liste des Sortie Stocks</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('sortie.create') }}" class="btn btn-primary shadow">Nouveau</a>

            <!-- Dropdown Actions -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-plus"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i>Export to Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2"></i>Export to PDF</a></li>
                </ul>
            </div>

            <!-- Search & Filter -->
            <form action="{{ route('sortie.index') }}" method="get" class="d-flex align-items-center gap-2 ms-2">
                <input type="text" class="form-control" name="date" placeholder="Date" value="{{ old('date',$date) }}">
                <select class="form-select" name="status">
                    <option disabled selected>Selectionner le statut</option>
                    <option value="0" {{ old('status',$status) == 0 ? 'selected' : '' }}>En attente</option>
                    <option value="1" {{ old('status',$status) == 1 ? 'selected' : '' }}>Approuvé</option>
                </select>
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            </form>

            <a href="{{ route('sortie.index') }}" class="btn btn-outline-secondary ms-2"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
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
                @forelse ($sorties as $value)
                    <tr>
                        <td>{{ $value->sortie_code }}</td>
                        <td>{{ $value->sortie_title }}</td>
                        <td class="text-center">
                            @if ($value->sortie_status == 0)
                                <span class="badge bg-warning text-dark">En attente</span>
                            @else
                                <span class="badge bg-success">Approuvé</span>
                            @endif
                        </td>
                        <td>{{ $value->created_at }}</td>
                        <td>{{ $value->user->name }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('sortie.show',['sortie' => $value]) }}" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if ($value->sortie_status == 0)
                                    <a href="{{ route('sortie.edit',['sortie' => $value]) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" data-href="{{ route('sortie.destroy',['sortie' => $value]) }}" onclick="handleDelete(this)" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
                    data: {
                        "_token" : "{{ csrf_token() }}",
                        "type" : "principal"
                    },
                    success: function(data) {
                        if(data.success) {
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
                                title: "Erreur, réessayez !",
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
