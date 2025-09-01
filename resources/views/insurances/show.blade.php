@extends('template')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fa fa-shield-alt me-2"></i> Détails Assurance
                    </h4>
                    <a href="{{ route('insurances.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-car me-2"></i>Voiture</span>
                            <span class="fw-bold">{{ $insurance->car->registration_number ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-building me-2"></i>Fournisseur</span>
                            <span>{{ $insurance->provider }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-file-contract me-2"></i>Numero de Police</span>
                            <span>{{ $insurance->policy_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-dollar-sign me-2"></i>Coût</span>
                            <span>{{ number_format($insurance->cost, 2) }} FBU</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-calendar-alt me-2"></i>Date de Début</span>
                            <span>{{ \Carbon\Carbon::parse($insurance->start_date)->format('d-m-Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-calendar-check me-2"></i>Date de Fin</span>
                            <span>{{ \Carbon\Carbon::parse($insurance->end_date)->format('d-m-Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-toggle-on me-2"></i>Active</span>
                            <span>
                                <span class="badge {{ $insurance->active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $insurance->active ? 'Yes' : 'No' }}
                                </span>
                            </span>
                        </li>
                        @if($insurance->document_scan)
                        <li class="list-group-item">
                            <span><i class="fa fa-file-pdf me-2"></i>Document</span>
                            <a href="{{ asset('storage/'.$insurance->document_scan) }}" target="_blank" class="ms-2">View</a>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="card-footer bg-light text-end rounded-bottom-4">
                    <a href="{{ route('insurances.edit', $insurance) }}" class="btn btn-warning me-2">
                        <i class="fa fa-pencil-alt me-1"></i> Edit
                    </a>
                    <form action="{{ route('insurances.destroy', $insurance) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Delete this insurance?')">
                            <i class="fa fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
