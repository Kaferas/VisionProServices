<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = \App\Models\Car::pluck('registration_number');

        $totalExpenses = \App\Models\Expense::selectRaw('car_id, SUM(amount) as total')
            ->groupBy('car_id')
            ->pluck('total')->toArray();
        $monthlyRefuels = \App\Models\Refueling::selectRaw('MONTH(date) as month, SUM(total_cost) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total')->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $totalMaintenances = \App\Models\Maintenance::selectRaw('car_id, SUM(cost) as total')
            ->groupBy('car_id')
            ->pluck('total')->toArray();

        $insuranceCounts = \App\Models\Insurance::selectRaw('car_id, COUNT(*) as total')
            ->groupBy('car_id')
            ->pluck('total')->toArray();
        return view('dashboard', compact('cars', 'totalExpenses', 'months', 'monthlyRefuels', 'totalMaintenances', 'insuranceCounts'));
    }


    public function overview() {
    $cars = \App\Models\Car::pluck('registration_number');

    // Expenses per car
    $totalExpenses = \App\Models\Expense::selectRaw('car_id, SUM(amount) as total')
                        ->groupBy('car_id')
                        ->pluck('total')->toArray();

    // Maintenance costs per car
    $totalMaintenances = \App\Models\Maintenance::selectRaw('car_id, SUM(cost) as total')
                            ->groupBy('car_id')
                            ->pluck('total')->toArray();

    // Refueling costs per car
    $totalRefuelings = \App\Models\Refueling::selectRaw('car_id, SUM(total_cost) as total')
                        ->groupBy('car_id')
                        ->pluck('total')->toArray();

    return view('dashboard_overview', compact('cars','totalExpenses','totalMaintenances','totalRefuelings'));
}

public function index_merge()
{
    // Get all car registration numbers
    $cars = \App\Models\Car::pluck('registration_number');

    // Total expenses per car
    $totalExpenses = \App\Models\Expense::selectRaw('car_id, SUM(amount) as total')
        ->groupBy('car_id')
        ->pluck('total')
        ->toArray();

    // Total maintenance costs per car
    $totalMaintenances = \App\Models\Maintenance::selectRaw('car_id, SUM(cost) as total')
        ->groupBy('car_id')
        ->pluck('total')
        ->toArray();

    // Total refueling costs per car
    $totalRefuelings = \App\Models\Refueling::selectRaw('car_id, SUM(total_cost) as total')
        ->groupBy('car_id')
        ->pluck('total')
        ->toArray();

    // Monthly refueling costs (for line chart)
    $monthlyRefuels = \App\Models\Refueling::selectRaw('MONTH(date) as month, SUM(total_cost) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total')
        ->toArray();

    // Months labels
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Insurance counts per car
    $insuranceCounts = \App\Models\Insurance::selectRaw('car_id, COUNT(*) as total')
        ->groupBy('car_id')
        ->pluck('total')
        ->toArray();

    // Return merged dashboard view with all datasets
    return view('dashboard', compact(
        'cars',
        'totalExpenses',
        'totalMaintenances',
        'totalRefuelings',
        'months',
        'monthlyRefuels',
        'insuranceCounts'
    ));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
