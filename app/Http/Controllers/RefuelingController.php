<?php

namespace App\Http\Controllers;

use App\Models\Refueling;
use App\Models\Car;
use Illuminate\Http\Request;

class RefuelingController extends Controller
{
    public function index(Request $request)
    {
       $query = Refueling::with('car');

    if ($request->filled('search')) {
        $query->where('station','like',"%{$request->search}%");
    }

    // if ($request->filled('fuel_type')) {
    //     $query->where('type',$request->fuel_type);
    // }

    $refuelings = $query->paginate(10);
    // $fuelTypes = Refueling::select('fuel_type')->distinct()->pluck('fuel_type');

    return view('refuelings.index',compact('refuelings'));
    }

    public function create()
    {
        $cars = Car::all();
        return view('refuelings.form', compact('cars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'liters' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0', // auto calculable
            'mileage' => 'required|integer|min:0',
            'date' => 'required|date',
            'station' => 'nullable|string',
        ]);

        // Auto calcul du coût si non fourni
        if (empty($data['total_cost'])) {
            $data['total_cost'] = $data['liters'] * $data['price_per_liter'];
        }

        Refueling::create($data);

        return redirect()->route('refuelings.index')->with('success', 'Refueling added successfully!');
    }

    public function show(Refueling $refueling)
    {
        return view('refuelings.show', compact('refueling'));
    }

    public function edit(Refueling $refueling)
    {
        $cars = Car::all();
        return view('refuelings.form', compact('refueling', 'cars'));
    }

    public function update(Request $request, Refueling $refueling)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'liters' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'date' => 'required|date',
            'station' => 'nullable|string',
        ]);

        if (empty($data['total_cost'])) {
            $data['total_cost'] = $data['liters'] * $data['price_per_liter'];
        }

        $refueling->update($data);

        return redirect()->route('refuelings.index')->with('success', 'Refueling updated successfully!');
    }

    public function destroy(Refueling $refueling)
    {
        $refueling->delete();
        return redirect()->route('refuelings.index')->with('success', 'Refueling deleted successfully!');
    }
}
