@extends('template')

@section("tab_name", "Visualiser Voiture")

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-dark text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-car-front-fill me-2"></i> Détails de la voiture
                    </h4>
                    <a href="{{ route('cars.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left-circle"></i> Retour
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-5 text-center">
                            @if($car->photo)
                                <img src="{{ asset('storage/'.$car->photo) }}" class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="bi bi-image fs-1"></i>
                                    <p class="ms-2 mb-0">No Photo</p>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-7">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong><i class="bi bi-hash me-2"></i>Numéro Matricule:</strong>
                                    {{ $car->registration_number }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="bi bi-car-front me-2"></i>Marque:</strong>
                                    {{ $car->brand }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="bi bi-cpu me-2"></i>Modèle:</strong>
                                    {{ $car->model }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="bi bi-calendar3 me-2"></i>Année:</strong>
                                    {{ $car->year }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="bi bi-speedometer2 me-2"></i>Kilométrage (km):</strong>
                                    <span>{{ number_format($car->mileage) }} km</span>
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="bi bi-check-circle me-2"></i>Status:</strong>
                                    <span class="badge {{ $car->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($car->status) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-end rounded-bottom-4">
                    <a href="{{ route('cars.edit',$car) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil-square"></i> Editer
                    </a>
                    <form action="{{ route('cars.destroy',$car) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
