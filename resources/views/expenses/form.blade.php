@extends('template')

@section('content')
<div class="container">
    <h1>{{ isset($expense) ? 'Edit Expense' : 'Add Expense' }}</h1>

    <form class="card p-3" action="{{ isset($expense) ? route('expenses.update',$expense) : route('expenses.store') }}" method="POST">
        @csrf
        @if(isset($expense)) @method('PUT') @endif

        <div class="mb-3">
            <label>Voiture</label>
            <select name="car_id" class="form-control" required>
                <option value="">Selectionner Voiture</option>
                @foreach($cars as $car)
                    <option value="{{ $car->id }}" {{ (isset($expense) && $expense->car_id==$car->id) ? 'selected' : '' }}>
                        {{ $car->registration_number }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Type depense</label>
            <select name="type" class="form-control" required>
                <option value="">Select Type</option>
                @foreach(['carburant','entretien','assurance','réparation','taxe','autre'] as $type)
                    <option value="{{ $type }}" {{ (isset($expense) && $expense->type==$type) ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Montant</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount',$expense->amount ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="{{ old('date',$expense->date ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Méthode de Paiement</label>
            <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method',$expense->payment_method ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description',$expense->description ?? '') }}</textarea>
        </div>

        <div class="d-flex">
            <button class="btn btn-success">{{ isset($expense) ? 'Update' : 'Save' }}</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
