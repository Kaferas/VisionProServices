@extends('template')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fa fa-id-badge me-2"></i> Driver Details
                    </h4>
                    <a href="{{ route('drivers.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-5 text-center">
                            @if($driver->photo)
                                <img src="{{ asset('storage/'.$driver->photo) }}" class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="fa fa-user-circle fs-1"></i>
                                    <span class="ms-2">No Photo</span>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-7">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fa fa-user me-2"></i>Name</span>
                                    <span class="fw-bold">{{ $driver->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fa fa-id-card me-2"></i>CNI</span>
                                    <span>{{ $driver->cni_driver }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fa fa-phone me-2"></i>Phone</span>
                                    <span>{{ $driver->phone }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fa fa-envelope me-2"></i>Email</span>
                                    <span>{{ $driver->email }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fa fa-id-card me-2"></i>License #</span>
                                    <span>{{ $driver->license_number }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fa fa-calendar-alt me-2"></i>License Expiry</span>
                                    <span>
                                        <span class="badge {{ \Carbon\Carbon::parse($driver->license_expiry)->isPast() ? 'bg-danger' : 'bg-success' }}">
                                            {{ $driver->license_expiry }}
                                        </span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-end rounded-bottom-4">
                    <a href="{{ route('drivers.edit',$driver) }}" class="btn btn-warning me-2">
                        <i class="fa fa-pencil-alt"></i> Edit
                    </a>
                    <form action="{{ route('drivers.destroy',$driver) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
