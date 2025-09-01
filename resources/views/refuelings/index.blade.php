@extends('template')


@section("tab_name", "listes des Ravitaillements")

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold"><i class="fa fa-gas-pump me-2"></i>⛽ Ravitaillement</h1>
        <a href="{{ route('refuelings.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle me-1"></i>Ravitaillement
        </a>
    </div>

    <!-- Search + Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('refuelings.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search Station</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Station name">
                </div>
                {{-- <div class="col-md-3">
                    <label class="form-label fw-semibold">Fuel Type</label>
                    <select name="fuel_type" class="form-select">
                        <option value="">-- Filter by Fuel Type --</option>
                        @foreach($fuelTypes as $ft)
                            <option value="{{ $ft }}" {{ request('fuel_type')==$ft ? 'selected':'' }}>
                                {{ ucfirst($ft) }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('refuelings.index') }}" class="btn btn-secondary w-100">
                        <i class="fa fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Refuelings Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Voiture</th>
                            <th>Type de Carburant</th>
                            <th>Station</th>
                            <th>Litres</th>
                            <th>Coût</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refuelings as $r)
                            <tr>
                                <td>{{ $r->car->registration_number ?? 'N/A' }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($r->fuel_type) }}</span></td>
                                <td>{{ $r->station }}</td>
                                <td>{{ $r->liters }}</td>
                                <td>${{ number_format($r->cost,2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($r->date)->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No refuelings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $refuelings->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
