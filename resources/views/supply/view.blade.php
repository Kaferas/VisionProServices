@extends('template')

@section('title')
    Détail de l'approvisionnement
@endsection

@section('content')
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mt-4 mb-3">
    <h2 class="h5 mb-2 mb-sm-0 me-auto">Détail de l'approvisionnement</h2>
    <div class="ms-auto">
        <button class="btn btn-primary me-2">Print</button>
    </div>
</div>

<div class="card overflow-hidden mt-3">
    <div class="border-bottom text-center text-sm-start">
        <div class="d-flex flex-column flex-lg-row px-3 px-lg-5 py-4">
            <div>
                <div class="text-muted"></div>
                <div class="h6 text-primary mt-2">Titre: {{ $supply->title }}</div>
                <div class="mt-1">Code: {{ $supply->supply_code }}</div>
            </div>

            @if($supply->supply_status == 1)
            <div class="text-center text-lg-end mt-3 mt-lg-0 ms-lg-auto">
                <div class="text-muted"></div>
                <div class="h6 text-primary mt-2">Approuvé par: {{ $supply->confirmed->name ?? "--" }}</div>
                <div class="mt-1">Approuvé le {{ date('d/m/Y',strtotime($supply->confirmed_or_rejected_at)) }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="px-3 px-sm-4 py-4 py-sm-5">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th class="text-end">Qté</th>
                        <th class="text-end">Prix</th>
                        <th class="text-end">SUBTOTAL</th>
                        @if($supply->supply_status == 0)
                        <th class="text-end">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($supplyDetails as $value)
                        <tr>
                            <td>{{ $value->product_name }}</td>
                            <td class="text-end">{{ $value->item_quantity }}</td>
                            <td class="text-end">{{ $value->purchase_price }}</td>
                            <td class="text-end">{{ $value->purchase_price * $value->item_quantity }}</td>
                            @if($supply->supply_status == 0)
                                <td class="text-end">
                                    <a data-href="{{ route('supply.destroy',['supply' => $supply]) }}"
                                       onclick="handleDelete(this)" data-id="{{ $value->id }}"
                                       class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="px-3 px-lg-5 py-3 d-flex flex-column flex-sm-row-reverse">
        @if ($supply->supply_status == 0)
        <div class="text-center text-sm-end ms-sm-auto">
            <button onclick="handleConfirm(this)" data-href="{{route('supply.update',['supply' => $supply])}}"
                class="btn btn-success mb-2">
                Approuver Tout
            </button>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js_content')
<script>
    function handleConfirm(th) {
        var url = $(th).data('href');

        Swal.fire({
            title: "Etes vous sur de vouloir approuvé l'approvisionnement?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non,Annuler',
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

    function handleDelete(th) {
        var url = $(th).data('href');
        var detail_id = $(th).data('id');

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
