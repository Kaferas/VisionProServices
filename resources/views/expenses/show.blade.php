@extends('template')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fa fa-money-bill-wave me-2"></i> Expense Details
                    </h4>
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-car me-2"></i>Voiture</span>
                            <span class="fw-bold">{{ $expense->car->registration_number ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-tags me-2"></i>Type depense</span>
                            <span><span class="badge bg-info text-dark">{{ ucfirst($expense->type) }}</span></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-dollar-sign me-2"></i>Montant</span>
                            <span class="fw-bold">{{ number_format($expense->amount, 2) }} FBU</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-calendar-alt me-2"></i>Date</span>
                            <span>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-credit-card me-2"></i>Méthode de Paiement</span>
                            <span>{{ $expense->payment_method ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span><i class="fa fa-sticky-note me-2"></i>Description</span>
                            <p class="mb-0">{{ $expense->description ?? '-' }}</p>
                        </li>
                    </ul>
                </div>

                <div class="card-footer bg-light text-end rounded-bottom-4">
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Retour
                    </a>
                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning ms-2">
                        <i class="fa fa-pencil-alt me-1"></i> Modifier
                    </a>
                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger ms-2" onclick="return confirm('Delete this expense?')">
                            <i class="fa fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
