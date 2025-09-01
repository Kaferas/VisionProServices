@extends('template')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fa fa-gas-pump me-2"></i> {{ isset($refueling) ? 'Modifier Ravitaillement' : 'Ajouter Ravitaillement' }}
                    </h4>
                    <a href="{{ route('refuelings.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Retour
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ isset($refueling) ? route('refuelings.update',$refueling) : route('refuelings.store') }}" method="POST">
                        @csrf
                        @if(isset($refueling)) @method('PUT') @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Voiture</label>
                            <select name="car_id" class="form-select" required>
                                <option value="">Selectionner Voiture</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ (isset($refueling) && $refueling->car_id==$car->id) ? 'selected' : '' }}>
                                        {{ $car->registration_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Litres</label>
                                <input type="number" step="0.01" name="liters" class="form-control" value="{{ old('liters',$refueling->liters ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Prix par Litre</label>
                                <input type="number" step="0.01" name="price_per_liter" class="form-control" value="{{ old('price_per_liter',$refueling->price_per_liter ?? '') }}" required>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">Total Coût</label>
                            <input type="number" step="0.01" name="total_cost" class="form-control" value="{{ old('total_cost',$refueling->total_cost ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date',$refueling->date ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Station de Carburant</label>
                            <input type="text" name="fuel_station" class="form-control" value="{{ old('fuel_station',$refueling->fuel_station ?? '') }}">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-success me-2">
                                <i class="fa fa-save me-1"></i> {{ isset($refueling) ? 'Update' : 'Save' }}
                            </button>
                            <a href="{{ route('refuelings.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
