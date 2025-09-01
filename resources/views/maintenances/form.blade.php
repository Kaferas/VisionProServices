@extends('template')

@section('content')
<div class="container">
    <h1>{{ isset($maintenance) ? 'Edit Maintenance' : 'Add Maintenance' }}</h1>

    <form class="card p-3" action="{{ isset($maintenance) ? route('maintenances.update',$maintenance) : route('maintenances.store') }}" method="POST">
        @csrf
        @if(isset($maintenance)) @method('PUT') @endif

        <div class="mb-3">
            <label>Car</label>
            <select name="car_id" class="form-control" required>
                <option value="">Select Car</option>
                @foreach($cars as $car)
                    <option value="{{ $car->id }}" {{ (isset($maintenance) && $maintenance->car_id==$car->id) ? 'selected' : '' }}>
                        {{ $car->registration_number }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Type de Service</label>
            <input type="text" name="service_type" class="form-control" value="{{ old('service_type',$maintenance->service_type ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Coût</label>
            <input type="number" step="0.01" name="cost" class="form-control" value="{{ old('cost',$maintenance->cost ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="{{ old('date',$maintenance->date ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Kilométrage au service</label>
            <input type="number" name="mileage_at_service" class="form-control" value="{{ old('mileage_at_service',$maintenance->mileage_at_service ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Garage</label>
            <input type="text" name="garage" class="form-control" value="{{ old('garage',$maintenance->garage ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control">{{ old('notes',$maintenance->notes ?? '') }}</textarea>
        </div>

        <div class="d-flex">
            <button class="btn btn-success">{{ isset($maintenance) ? 'Update' : 'Save' }}</button>
        <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
