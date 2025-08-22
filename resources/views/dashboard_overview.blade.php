@extends('template')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center"><i class="fa fa-chart-bar me-2"></i>Financial Dashboard</h1>

    <div class="card shadow-lg rounded-4 p-4">
        <canvas id="financialChart" height="550"></canvas>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = {!! json_encode($cars->pluck('registration_number')) !!}; // use registration numbers for readability

const data = {
    labels: labels,
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
};

const config = {
    type: 'bar',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top', labels: { font: { size: 14 } } },
            title: { display: true, text: 'Expenses, Maintenances & Refuelings per Car', font: { size: 18 } }
        },
        scales: {
            y: { beginAtZero: true, ticks: { font: { size: 12 } } },
            x: { ticks: { font: { size: 12 } } }
        }
    },
};

new Chart(
    document.getElementById('financialChart'),
    config
);
</script>
@endsection
