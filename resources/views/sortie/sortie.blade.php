@extends('template')

@section('title')
    Détail de la Sortie
@endsection

@section('content')
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3">
        <h2 class="h5 me-auto">Détail de la Sortie</h2>
        <div class="mt-2 mt-sm-0">
            <button class="btn btn-primary me-2">Print</button>
        </div>
    </div>

    <!-- Détails de la sortie -->
    <div class="card mb-3">
        <div class="card-body d-flex flex-column flex-lg-row justify-content-between">
            <div>
                <div class="text-muted"></div>
                <div class="h6 text-primary mt-1">Titre: {{ $sortie->title }}</div>
                <div>Code: {{ $sortie->sortie_code }}</div>
            </div>

            @if($sortie->sortie_status == 1)
            <div class="text-lg-end mt-3 mt-lg-0">
                <div class="text-muted"></div>
                <div class="h6 text-primary mt-1">Approuvé par: {{ $sortie->confirmed->name ?? "--" }}</div>
                <div>Approuvé le {{ date('d/m/Y', strtotime($sortie->confirmed_at)) }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Table des articles -->
    <div class="card">
        <div class="table-responsive p-3">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th class="text-end">Qté Sortie</th>
                        <th class="text-end">Prix</th>
                        <th class="text-end">Subtotal</th>
                        @if($sortie->sortie_status == 0)
                            <th class="text-end">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sortieDetails as $value)
                    <tr>
                        <td>{{ $value->item_sortie_name }}</td>
                        <td class="text-end">{{ $value->item_sortie_quantity }}</td>
                        <td class="text-end">{{ $value->item_sortie_price }}</td>
                        <td class="text-end">{{ $value->item_sortie_price * $value->item_sortie_quantity }}</td>
                        @if($sortie->sortie_status == 0)
                        <td class="text-end">
                            <button type="button" data-href="{{ route('sortie.destroy',['sortie' => $sortie]) }}"
                                data-id="{{ $value->id }}" onclick="handleDelete(this)" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bouton Approver -->
    @if ($sortie->sortie_status == 0)
    <div class="d-flex justify-content-end mt-3">
        <button onclick="handleConfirm(this)" data-href="{{route('sortie.update',['sortie' => $sortie])}}"
            class="btn btn-success">
            Approuver Tout
        </button>
    </div>
    @endif

</div>
@endsection

@section('js_content')
<script>
    function handleConfirm(th) {
        var url = $(th).data('href');

        Swal.fire({
            title: "Êtes-vous sûr de vouloir approuver la sortie des articles ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non, Annuler',
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'PUT',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "type": "confirm",
                    },
                    success: function(data) {
                        if(data.success) {
                            Swal.fire({position:'top-end', icon:'success', title:data.messages, showConfirmButton:false, timer:3000});
                            window.location.reload();
                        } else {
                            Swal.fire({position:'top-end', icon:'warning', title:"Erreur, réessayez !", showConfirmButton:false, timer:3000});
                        }
                    }
                });
            }
        });
    }

    function handleDelete(th) {
        var url = $(th).data('href');
        var detail_id = $(th).data('id');

        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cet article ?",
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
                        "_token": "{{ csrf_token() }}",
                        "type": "detail",
                        "detail": detail_id,
                    },
                    success: function(data) {
                        if(data.success) {
                            Swal.fire({position:'top-end', icon:'success', title:data.messages, showConfirmButton:false, timer:3000});
                            window.location.reload();
                        } else {
                            Swal.fire({position:'top-end', icon:'warning', title:"Erreur, réessayez !", showConfirmButton:false, timer:3000});
                        }
                    }
                });
            }
        });
    }
</script>
@endsection
