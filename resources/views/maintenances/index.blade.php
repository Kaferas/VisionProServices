@extends('template')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fa fa-tools me-2"></i>🛠️ Maintenances</h3>
        <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle me-1"></i> Add Maintenance
        </a>
    </div>

    <!-- Search + Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('maintenances.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Description">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">-- Filter by Type --</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type')==$type ? 'selected':'' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('maintenances.index') }}" class="btn btn-secondary w-100">
                        <i class="fa fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Maintenances Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Car</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenances as $m)
                            <tr>
                                <td>{{ $m->car->registration_number ?? 'N/A' }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($m->type) }}</span></td>
                                <td>{{ $m->description }}</td>
                                <td>${{ number_format($m->cost,2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($m->date)->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('maintenances.edit',$m) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('maintenances.destroy',$m) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this maintenance?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $maintenances->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
