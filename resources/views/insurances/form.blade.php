@extends('template')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fa fa-shield-alt me-2"></i> {{ isset($insurance) ? 'Edit Insurance' : 'Add Insurance' }}
                    </h4>
                    <a href="{{ route('insurances.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ isset($insurance) ? route('insurances.update',$insurance) : route('insurances.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($insurance)) @method('PUT') @endif

                        <div class="mb-3">
                            <label class="form-label">Car</label>
                            <select name="car_id" class="form-select" required>
                                <option value="">Select Car</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ (isset($insurance) && $insurance->car_id==$car->id) ? 'selected' : '' }}>
                                        {{ $car->registration_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Provider</label>
                            <input type="text" name="provider" class="form-control" value="{{ old('provider',$insurance->provider ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Policy Number</label>
                            <input type="text" name="policy_number" class="form-control" value="{{ old('policy_number',$insurance->policy_number ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cost</label>
                            <input type="number" step="0.01" name="cost" class="form-control" value="{{ old('cost',$insurance->cost ?? '') }}" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date',$insurance->start_date ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date',$insurance->end_date ?? '') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Active</label>
                            <select name="active" class="form-select">
                                <option value="1" {{ (isset($insurance) && $insurance->active) ? 'selected':'' }}>Yes</option>
                                <option value="0" {{ (isset($insurance) && !$insurance->active) ? 'selected':'' }}>No</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Document Scan</label><br>
                            @if(isset($insurance) && $insurance->document_scan)
                                <a href="{{ asset('storage/'.$insurance->document_scan) }}" target="_blank">
                                    <i class="fa fa-file-pdf me-1"></i> View Document
                                </a><br>
                            @endif
                            <input type="file" name="document_scan" class="form-control">
                        </div>

                        <div class="text-end">
                            <button class="btn btn-success me-2">
                                <i class="fa fa-save me-1"></i> {{ isset($insurance) ? 'Update' : 'Save' }}
                            </button>
                            <a href="{{ route('insurances.index') }}" class="btn btn-secondary">
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
