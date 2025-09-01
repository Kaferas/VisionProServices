@extends('template')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fa fa-id-badge me-2"></i> {{ isset($driver) ? 'Modifier Chauffeur' : 'Ajouter Chauffeur' }}
                    </h4>
                    <a href="{{ route('drivers.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ isset($driver) ? route('drivers.update',$driver) : route('drivers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($driver)) @method('PUT') @endif

                        <div class="mb-3">
                            <label class="form-label">Nom Chauffeur</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name',$driver->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CNI Chauffeur</label>
                            <input type="text" name="cni_driver" class="form-control" value="{{ old('cni_driver',$driver->cni_driver ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone',$driver->phone ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email',$driver->email ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Numero Permis</label>
                            <input type="text" name="license_number" class="form-control" value="{{ old('license_number',$driver->license_number ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date d'Expiration Permis</label>
                            <input type="date" name="license_expiry" class="form-control" value="{{ old('license_expiry',$driver->license_expiry ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Photo</label><br>
                            @if(isset($driver) && $driver->photo)
                                <img src="{{ asset('storage/'.$driver->photo) }}" class="rounded shadow-sm mb-2" width="120"><br>
                            @endif
                            <input type="file" name="photo" class="form-control">
                        </div>

                        <div class="text-end">
                            <button class="btn btn-success me-2">
                                <i class="fa fa-save me-1"></i> {{ isset($driver) ? 'Update' : 'Save' }}
                            </button>
                            <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
