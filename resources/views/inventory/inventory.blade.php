@extends('template')

@section('title')
    Détail de l'Inventaire
@endsection

@section('content')
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-4">
        <h2 class="h5 mb-3 mb-sm-0 me-auto">Détail de l'Inventaire</h2>
        <div>
            <button class="btn btn-primary">Print</button>
        </div>
    </div>

    <!-- Inventory Details Card -->
    <div class="card mb-4">
        <div class="card-body">

            <div class="d-flex flex-column flex-lg-row justify-content-between mb-4">
                <div>
                    <div class="text-muted">Titre</div>
                    <div class="h6 text-primary mt-1">Titre: {{ $inventory->inventory_title }}</div>
                    <div>Code: {{ $inventory->inventory_code }}</div>
                </div>

                @if($inventory->inventory_status == 1)
                    <div class="text-lg-end mt-3 mt-lg-0">
                        <div class="text-muted">Approuvé par</div>
                        <div class="h6 text-primary mt-1">Approuvé par: {{ $inventory->confirmed->name ?? "--" }}</div>
                        <div>Approuvé le {{ date('d/m/Y',strtotime($inventory->confirmed_at)) }}</div>
                    </div>
                @endif
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Article</th>
                            <th class="text-end">Qté System</th>
                            <th class="text-end">Qté Physique</th>
                            <th class="text-end">Prix</th>
                            @if($inventory->inventory_status == 0)
                                <th class="text-end">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventoryDetails as $value)
                            <tr>
                                <td>{{ $value->item_inventory_name }}</td>
                                <td class="text-end">{{ $value->item_system_quantity }}</td>
                                <td class="text-end">{{ $value->item_physic_quantity }}</td>
                                <td class="text-end">{{ $value->item_inventory_price }}</td>
                                @if($inventory->inventory_status == 0)
                                    <td class="text-end">
                                        <button type="button" data-href="{{ route('inventory.destroy',['inventory' => $inventory]) }}"
                                            data-id="{{ $value->id }}" onclick="handleDelete(this)"
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Approve Button -->
            @if ($inventory->inventory_status == 0)
                <div class="d-flex justify-content-end mt-4">
                    <button onclick="handleConfirm(this)"
                        data-href="{{route('inventory.update',['inventory' => $inventory])}}"
                        class="btn btn-success">
                        Approuver Tout
                    </button>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@section('js_content')
<script>
    function handleConfirm(th) {
        var url = $(th).data('href');

        Swal.fire({
            title: "Etes-vous sûr de vouloir approuver l'inventaire du stock?",
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
                        "_token" : "{{ csrf_token() }}",
                        "type" : "confirm",
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

    function handleDelete(th) {
        var url = $(th).data('href');
        var detail_id = $(th).data('id');

        Swal.fire({
            title: "Etes-vous sûr de vouloir supprimer cette donnée?",
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
                        "type" : "detail",
                        "detail" : detail_id,
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
