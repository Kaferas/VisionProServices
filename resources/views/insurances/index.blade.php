@extends('template')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fa fa-shield-alt me-2"></i>🛡️ Assurances</h3>
        <a href="{{ route('insurances.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle me-1"></i> Add Insurance
        </a>
    </div>

    <!-- Search + Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('insurances.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Policy Number">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ request('expiry_date') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('insurances.index') }}" class="btn btn-secondary w-100">
                        <i class="fa fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Insurances Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Car</th>
                            <th>Policy Number</th>
                            <th>Provider</th>
                            <th>Cost</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($insurances as $i)
                            <tr>
                                <td>{{ $i->car->registration_number ?? 'N/A' }}</td>
                                <td>{{ $i->policy_number }}</td>
                                <td>{{ $i->provider }}</td>
                                <td>${{ number_format($i->cost, 2) }}</td>
                                <td>
                                    <span class="badge {{ \Carbon\Carbon::parse($i->expiry_date)->isPast() ? 'bg-danger' : 'bg-success' }}">
                                        {{ \Carbon\Carbon::parse($i->expiry_date)->format('d-m-Y') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No insurances found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $insurances->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
