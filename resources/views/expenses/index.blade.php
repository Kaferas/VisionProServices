@extends('template')

@section("tab_name", "listes des Depenses")


@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fa fa-money-bill-wave me-2"></i>💰 Depenses</h3>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle me-1"></i> Add New Expense
        </a>
    </div>

    <!-- Search + Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('expenses.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Description">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">-- Filter by Type --</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
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
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary w-100">
                        <i class="fa fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Voiture Matricule</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->car->registration_number ?? 'N/A' }}</td>
                                {{-- <td><span class="badge bg-info text-dark">{{ ucfirst($expense->type) }}</span></td> --}}
                                @switch($expense->type)
                                @case('carburant')
                                    <td><span class="badge bg-success">Carburant</span></td>
                                    @break
                                @case('entretien')
                                    <td><span class="badge bg-warning">Entretien</span></td>
                                    @break
                                @case('assurance')
                                    <td><span class="badge bg-info">assurance</span></td>
                                    @break
                                @case('réparation')
                                    <td><span class="badge bg-danger">réparation</span></td>
                                    @break
                                @default
                                    <td><span class="badge bg-secondary">Other</span></td>
                            @endswitch
                                <td>{{ $expense->description }}</td>
                                <td>{{ number_format($expense->amount, 2) }} FBU</td>
                                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('expenses.edit',$expense) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('expenses.destroy',$expense) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this expense?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No expenses found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $expenses->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
