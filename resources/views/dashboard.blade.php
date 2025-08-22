@extends('template')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center"><i class="fa fa-tachometer-alt me-2"></i> Tableau de Bord </h1>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4">
                <h5 class="card-title"><i class="fa fa-dollar-sign me-2"></i>Total Expenses per Car</h5>
                <canvas id="expensesChart" height="350"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4">
                <h5 class="card-title"><i class="fa fa-gas-pump me-2"></i>Monthly Refueling Costs</h5>
                <canvas id="refuelingChart" height="350"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4">
                <h5 class="card-title"><i class="fa fa-tools me-2"></i>Maintenance Costs per Car</h5>
                <canvas id="maintenanceChart" height="350"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4">
                <h5 class="card-title"><i class="fa fa-shield-alt me-2"></i>Insurance Count per Car</h5>
                <canvas id="insuranceChart" height="350"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Utility function for plucking car registration numbers
    const carLabels = {!! json_encode($cars->pluck('registration_number')) !!};

    // Expenses per Car
    new Chart(document.getElementById('expensesChart'), {
        type: 'bar',
        data: {
            labels: carLabels,
            datasets: [{
                label: 'Total Expenses ($)',
                data: {!! json_encode($totalExpenses) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // Monthly Refueling Costs
    new Chart(document.getElementById('refuelingChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Refueling Costs ($)',
                data: {!! json_encode($monthlyRefuels) !!},
                fill: true,
                backgroundColor: 'rgba(255, 206, 86, 0.4)',
                borderColor: 'rgba(255, 206, 86, 1)',
                tension: 0.3
            }]
        },
        options: { responsive: true }
    });

    // Maintenance Costs per Car
    new Chart(document.getElementById('maintenanceChart'), {
        type: 'bar',
        data: {
            labels: carLabels,
            datasets: [{
                label: 'Maintenance ($)',
                data: {!! json_encode($totalMaintenances) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // Insurance Count per Car
    new Chart(document.getElementById('insuranceChart'), {
        type: 'bar',
        data: {
            labels: carLabels,
            datasets: [{
                label: 'Insurance Count',
                data: {!! json_encode($insuranceCounts) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection
