@extends('template')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="fw-bold">🚗 Cars Management</h3>
        <div>
            <a href="{{ route('cars.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> ➕ New Car
            </a>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="mb-3 d-flex justify-content-end gap-2">
        <a title="Export to Excel" href="{{ route('cars.export.excel', request()->all()) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> 📊 Excel
        </a>
        <a title="Export to PDF" href="{{ route('cars.export.pdf', request()->all()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> 📄 PDF
        </a>
    </div>

    <!-- Search + Filter --
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('cars.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Registration number or model">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Brand</label>
                    <select name="brand" class="form-select">
                        <option value="">-- All Brands --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-70">
                        <i class="bi bi-filter"></i> 🔍 Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('cars.index') }}" class="btn btn-secondary w-70">
                        <i class="bi bi-arrow-repeat"></i> 🔄 Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Cars Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle text-center"">
                    <thead class="table-dark">
                        <tr>
                            <th>Photo</th>
                            <th>Registration</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Year</th>
                            <th>Mileage (km)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cars as $car)
                            <tr>
                                <td>
                                    @if ($car->photo)
                                        <img src="{{ asset('storage/' . $car->photo) }}" class="rounded shadow-sm" width="80" height="60" style="object-fit:cover;">
                                    @else
                                        <span class="text-muted">No photo</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $car->registration_number }}</td>
                                <td>{{ $car->brand }}</td>
                                <td>{{ $car->model }}</td>
                                <td>{{ $car->year }}</td>
                                <td>{{ number_format($car->mileage) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('cars.show', $car) }}" class="btn btn-sm btn-outline-info me-1">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('cars.edit', $car) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('cars.destroy', $car) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No cars found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $cars->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
