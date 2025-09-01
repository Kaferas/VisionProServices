@extends('template')

@section("tab_name", "listes des Chauffeurs")

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fa fa-id-badge me-2"></i> 👨‍✈️ Chauffeurs</h3>
        <a href="{{ route('drivers.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle me-1"></i> Add New Driver
        </a>
    </div>

    <!-- Search + Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('drivers.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or License">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">License Expiry</label>
                    <input type="date" name="license_expiry" value="{{ request('license_expiry') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('drivers.index') }}" class="btn btn-secondary w-100">
                        <i class="fa fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Drivers Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Nom du Chauffeur</th>
                            <th>Numero Permis</th>
                            <th>Date d'Expiration</th>
                            <th>Téléphone</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td>
                                    @if($driver->photo)
                                        <img src="{{ asset('storage/'.$driver->photo) }}" class="rounded shadow-sm" width="60" height="60" style="object-fit:cover;">
                                    @else
                                        <i class="fa fa-user-circle text-muted fs-3"></i>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $driver->name }}</td>
                                <td>{{ $driver->license_number }}</td>
                                <td>
                                    <span class="badge {{ \Carbon\Carbon::parse($driver->license_expiry)->isPast() ? 'bg-danger' : 'bg-success' }}">
                                        {{ $driver->license_expiry }}
                                    </span>
                                </td>
                                <td>{{ $driver->phone }}</td>
                                <td class="text-center">
                                    <a href="{{ route('drivers.edit',$driver) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('drivers.destroy',$driver) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this driver?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No drivers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $drivers->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
