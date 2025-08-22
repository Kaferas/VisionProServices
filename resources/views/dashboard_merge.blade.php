@extends('template')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center"><i class="fa fa-chart-bar me-2"></i>Fleet Analytics</h1>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="fleetTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Financial Overview</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">Fleet Dashboard</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="fleetTabsContent">

        <!-- Financial Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="card shadow-lg rounded-4 p-4 mb-4" style="height:400px;">
                <canvas id="financialChart"></canvas>
            </div>
        </div>

        <!-- Fleet Dashboard Tab -->
        <div class="tab-pane fade" id="dashboard" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow rounded-4 p-4">
                        <h5 class="card-title"><i class="fa fa-dollar-sign me-2"></i>Total Expenses per Car</h5>
                        <canvas id="expensesChart" height="150"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow rounded-4 p-4">
                        <h5 class="card-title"><i class="fa fa-gas-pump me-2"></i>Monthly Refueling Costs</h5>
                        <canvas id="refuelingChart" height="150"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow rounded-4 p-4">
                        <h5 class="card-title"><i class="fa fa-tools me-2"></i>Maintenance Costs per Car</h5>
                        <canvas id="maintenanceChart" height="150"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow rounded-4 p-4">
                        <h5 class="card-title"><i class="fa fa-shield-alt me-2"></i>Insurance Count per Car</h5>
                        <canvas id="insuranceChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS (for tabs) and Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Labels for cars
    const carLabels = {!! json_encode($cars->pluck('registration_number')) !!};

    // ----- Financial Overview Chart -----
    new Chart(document.getElementById('financialChart'), {
        type: 'bar',
        data: {
            labels: carLabels,
            datasets: [
                {
                    label: 'Expenses ($)',
                    data: {!! json_encode($totalExpenses) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Maintenances ($)',
                    data: {!! json_encode($totalMaintenances) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Refuelings ($)',
                    data: {!! json_encode($totalRefuelings) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Expenses, Maintenances & Refuelings per Car' }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    // ----- Dashboard Charts -----

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
ss
