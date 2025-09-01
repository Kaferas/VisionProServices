@extends('template')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fa fa-tools me-2"></i> Maintenance Details
                    </h4>
                    <a href="{{ route('maintenances.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-car me-2"></i> Voiture</span>
                            <span class="fw-bold">{{ $maintenance->car->registration_number ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-cogs me-2"></i> Type de Service</span>
                            <span>{{ $maintenance->service_type }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-dollar-sign me-2"></i> Coût</span>
                            <span>${{ number_format($maintenance->cost, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-calendar-alt me-2"></i> Date</span>
                            <span>{{ \Carbon\Carbon::parse($maintenance->date)->format('d-m-Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-tachometer-alt me-2"></i> Kilométrage au Service</span>
                            <span>{{ $maintenance->mileage_at_service }} km</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-building me-2"></i> Garage</span>
                            <span>{{ $maintenance->garage ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span><i class="fa fa-sticky-note me-2"></i> Notes</span>
                            <p class="mb-0 ms-4">{{ $maintenance->notes ?? '-' }}</p>
                        </li>
                    </ul>
                </div>

                <div class="card-footer bg-light text-end rounded-bottom-4">
                    <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-warning me-2">
                        <i class="fa fa-pencil-alt me-1"></i> Editer
                    </a>
                    <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Delete this maintenance?')">
                            <i class="fa fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
