<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Car;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with('car');

    if ($request->filled('search')) {
        $query->where('description','like',"%{$request->search}%");
    }

    if ($request->filled('type')) {
        $query->where('service_type',$request->type);
    }

    $maintenances = $query->paginate(10);
    $types = Maintenance::select('service_type')->distinct()->pluck('service_type');
    $type=$request->type;

    return view('maintenances.index',compact('maintenances','types','type'));
    }

    public function create()
    {
        $cars = Car::all();
        return view('maintenances.form', compact('cars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'service_type' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'date' => 'required|date',
            'mileage_at_service' => 'required|integer|min:0',
            'garage' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Maintenance::create($data);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance added successfully!');
    }

    public function show(Maintenance $maintenance)
    {
        return view('maintenances.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        $cars = Car::all();
        return view('maintenances.form', compact('maintenance', 'cars'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'service_type' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'date' => 'required|date',
            'mileage_at_service' => 'required|integer|min:0',
            'garage' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $maintenance->update($data);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance updated successfully!');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success', 'Maintenance deleted successfully!');
    }
}
