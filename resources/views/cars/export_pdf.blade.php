<!DOCTYPE html>
<html>
<head>
    <title>Cars Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
            background-color: #f8f9fa;
        }
        h2 {
            margin-bottom: 30px;
            color: #0d6efd;
            text-align: center;
        }
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        table th {
            background-color: #0d6efd;
            color: #fff;
        }
        table tbody tr:hover {
            background-color: #e9f5ff;
        }
    </style>
</head>
<body>
    <h2>🚗 Cars Report</h2>
    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Registration</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Mileage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cars as $car)
                    <tr>
                        <td>{{ $car->registration_number }}</td>
                        <td>{{ $car->brand }}</td>
                        <td>{{ $car->model }}</td>
                        <td>{{ $car->year }}</td>
                        <td>{{ number_format($car->mileage) }} km</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
