@extends('template')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0"><i class="fa fa-gas-pump me-2"></i> Refueling Details</h4>
                    <a href="{{ route('refuelings.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><b>Car:</b> {{ $refueling->car->registration_number ?? 'N/A' }}</li>
                        <li class="list-group-item"><b>Liters:</b> {{ $refueling->liters }}</li>
                        <li class="list-group-item"><b>Price per Liter:</b> ${{ number_format($refueling->price_per_liter,2) }}</li>
                        <li class="list-group-item"><b>Total Cost:</b> ${{ number_format($refueling->total_cost,2) }}</li>
                        <li class="list-group-item"><b>Date:</b> {{ \Carbon\Carbon::parse($refueling->date)->format('d-m-Y') }}</li>
                        <li class="list-group-item"><b>Fuel Station:</b> {{ $refueling->fuel_station ?? '-' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
