@extends('template')

@section('content')
<div class="container-fluid my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="bi bi-car-front-fill me-2"></i>
                        {{ isset($car) ? 'Edit Car' : 'Add New Car' }}
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ isset($car) ? route('cars.update',$car) : route('cars.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @if(isset($car)) @method('PUT') @endif

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Registration Number</label>
                            <input type="text" name="registration_number"
                                   value="{{ old('registration_number',$car->registration_number ?? '') }}"
                                   class="form-control rounded-3" placeholder="e.g. ABC-1234" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Brand</label>
                            <input type="text" name="brand"
                                   value="{{ old('brand',$car->brand ?? '') }}"
                                   class="form-control rounded-3" placeholder="e.g. Toyota" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Model</label>
                            <input type="text" name="model"
                                   value="{{ old('model',$car->model ?? '') }}"
                                   class="form-control rounded-3" placeholder="e.g. Corolla" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Year</label>
                            <input type="number" name="year"
                                   value="{{ old('year',$car->year ?? '') }}"
                                   class="form-control rounded-3" placeholder="e.g. 2020" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mileage</label>
                            <input type="number" name="mileage"
                                   value="{{ old('mileage',$car->mileage ?? '') }}"
                                   class="form-control rounded-3" placeholder="e.g. 55000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Photo</label>
                            @if(isset($car) && $car->photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$car->photo) }}" width="120" class="img-thumbnail rounded">
                                </div>
                            @endif
                            <input type="file" name="photo" class="form-control rounded-3">
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary me-2 px-4">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button class="btn btn-success px-4">
                                <i class="bi bi-save"></i> {{ isset($car) ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
