@extends('template')

@section('title')
    Listes des unités de mesures
@endsection

@section('content')
    <h2 class="h4 mt-4">Unité de mesures</h2>

    <div class="row mt-3">
        <!-- Action Buttons -->
        <div class="col-12 d-flex flex-wrap align-items-center mb-3">
            <a data-href="{{ route('unity.store') }}" onclick="toggleModal(this)" class="btn btn-primary me-2 mb-2">Nouveau</a>

            <div class="dropdown me-2 mb-2">
                <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-plus"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#"><i class="fa fa-print me-2"></i>Print</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa fa-file-excel me-2"></i>Export to Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa fa-file-pdf me-2"></i>Export to PDF</a></li>
                </ul>
            </div>

            <div class="d-none d-md-block mx-auto text-secondary mb-2">Showing 1 to 10 of 150 entries</div>

            <div class="ms-auto mb-2">
                <form action="{{ route('unity.index') }}" method="get" class="d-flex">
                    <input type="text" class="form-control me-2" name="search" value="{{ old('search',$search) }}" placeholder="Search...">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <a href="{{ route('unity.index') }}" class="btn btn-light ms-2 mb-2"><i class="fa fa-refresh"></i></a>
        </div>

        <!-- Table -->
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover module_data_table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Créer Par</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($unities as $key => $value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->title }}</td>
                                    <td>{{ $value->user->name ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <a data-href="{{ route('unity.update',['unity' => $value]) }}"
                                               data-unity="{{ json_encode($value) }}" onclick="toggleEditModal(this)"
                                               class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                                            <a data-href="{{ route('unity.destroy',['unity' => $value]) }}"
                                               onclick="handleDelete(this)" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="formModuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <x-modal-header>Ajout d'une unité de mesure</x-modal-header>
                <form id="add_data_form">
                    <x-modal-body>
                        @csrf
                        <div class="mb-3">
                            <x-input :type="'text'" :label="'Nom de l\'unité de mesure'" :name="'title'" :value="''" :required="true" />
                        </div>
                    </x-modal-body>
                    <x-modal-footer></x-modal-footer>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js_content')
<script>
    function toggleModal(th) {
        var url = $(th).data('href');
        $('#title').val('');
        $('#add_data_form').attr('action', url);
        $('#add_data_form').attr('method', 'POST');
        $('#add_data_form').trigger('reset');
        $('#formModuleModal').modal('show');
    }

    function toggleEditModal(th) {
        var data = $(th).data('unity');
        var up_url = $(th).data('href');

        $('#title').val(data.title);
        $('#add_data_form').attr('action', up_url);
        $('#add_data_form').attr('method', 'PUT');

        $('#formModuleModal').modal('show');
    }

    function handleAddData(th) {
        $(th).attr('disabled', true);
        $('#infos').attr('hidden', false);
        var form = $('#add_data_form');

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'json',
            data: form.serialize(),
            success: function(data) {
                if (data.success) {
                    $(th).attr('disabled', false);
                    $('#infos').attr('hidden', true);
                    $('#formModuleModal').modal('hide');
                    $('.module_data_table').load(' .module_data_table');
                    form.trigger("reset");
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data.messages,
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    var errors = Object.values(data.messages);
                    var html = "<ul class='list-group'>";
                    errors.forEach(element => {
                        html += `<li class="list-group-item text-danger">${element}</li>`;
                    });
                    html += "</ul>";
                    Swal.fire({
                        icon: 'error',
                        title: "Oups, il y a des erreurs",
                        html: html,
                    });
                    $(th).attr('disabled', false);
                    $('#infos').attr('hidden', true);
                }
            }
        });
    }

    function handleDelete(th) {
        var url = $(th).data('href');

        Swal.fire({
            title: "Etes vous sur de vouloir supprimer les données?",
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
                    data: {"_token" : "{{ csrf_token() }}"},
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
                                icon: 'warning',
                                title: "Error, retry!",
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
