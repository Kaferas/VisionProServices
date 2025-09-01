@extends('template')

@section('title', 'Listes des Utilisateurs')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Listes des Utilisateurs</h2>

    <!-- Search & Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
       <form action="{{ route('users.index') }}" method="get" class="d-flex mb-2">
    <input type="text" name="search" value="{{ old('search', '') }}"
           class="form-control me-2 flex-grow-1" placeholder="Search...">
    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
</form>


        <div class="d-flex align-items-center mb-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2"><i class="bi bi-arrow-clockwise"></i> Refresh</a>
            <a href="{{ route('users.create') }}" class="btn btn-success me-2">Nouveau Utilisateur</a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($all_users as $k => $item)
                <tr>
                    <td>{{ $k + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->roles[0]->name ?? '-' }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->isBanned == 0 ? 'En cours' : 'Banni' }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('users.edit', $item) }}" class="btn btn-outline-primary btn-sm" title="Modifier Utilisateur"><i class="bi bi-pencil"></i></a>
                            <button type="button" class="btn btn-outline-danger btn-sm" title="Supprimer Utilisateur"><i class="bi bi-trash"></i></button>
                            <button type="button" class="btn btn-outline-warning btn-sm" title="Bannir Utilisateur"><i class="bi bi-x-circle"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Example -->
    <div class="modal fade" id="avoir_invoice_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-4">
                <h5 class="modal-title mb-3">Facture Avoir du N: <span class="text-primary" id="id_number"></span></h5>
                <form id="avoirForm" method="post">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Article</th>
                                    <th>Quantity</th>
                                    <th>Prix</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="appenderDetails"></tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
