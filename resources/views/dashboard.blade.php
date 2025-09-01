@extends('template')

@section("tab_name", "Tableau de Bord")

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center"><i class="fa fa-tachometer-alt me-2"></i> Tableau de Bord </h1>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4">
                <h5 class="card-title"><i class="fa fa-dollar-sign me-2"></i>
Dépenses totales par voiture</h5>
                <canvas id="expensesChart" height="300"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4">
                <h5 class="card-title"><i class="fa fa-gas-pump me-2"></i>Dépenses mensuelles de ravitaillement</h5>
                <canvas id="refuelingChart" height="300"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4">
                <h5 class="card-title"><i class="fa fa-tools me-2"></i>Dépenses de maintenance par voiture</h5>
                <canvas id="maintenanceChart" height="300"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow rounded-4 p-4">
                <h5 class="card-title"><i class="fa fa-shield-alt me-2"></i>Nombre d'Assurances par Voiture</h5>
                <canvas id="insuranceChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js_content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Utility function for plucking car registration numbers
    const cars = {!! json_encode($cars) !!};
    const months = {!! json_encode($months) !!};
    const totalMaintenances = {!! json_encode($totalMaintenances) !!};
    const insuranceCounts = {!! json_encode($insuranceCounts) !!};
    console.log(totalMaintenances,months,cars);
    // Expenses per Car
    new Chart(document.getElementById('expensesChart'), {
        type: 'polarArea',
        data: {
            labels: cars,
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
        type: 'doughnut',
        data: {
            labels: months,
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
            labels: cars,
            datasets: [{
                label: 'Maintenance ($)',
                data: {!! json_encode($totalMaintenances) !!},
                backgroundColor: 'rgba(225, 99, 182, 0.6)',
                borderColor: 'rgba(255, 39, 112, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // Insurance Count per Car
    new Chart(document.getElementById('insuranceChart'), {
        type: 'bar',
        data: {
            labels: cars,
            datasets: [{
                label: 'Insurance Count',
                data: {!! json_encode($insuranceCounts) !!},
                backgroundColor: 'rgba(75, 92, 192, 0.6)',
                borderColor: 'rgba(5, 192, 12, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection
