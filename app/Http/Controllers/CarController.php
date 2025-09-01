<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CarsExport;
use App\Models\CarCategory;
use Barryvdh\DomPDF\Facade\Pdf;


class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();
        // Search by registration or model
        if ($request->filled('search')) {
            $query->where('registration_number', 'like', "%{$request->search}%")
                ->orWhere('model', 'like', "%{$request->search}%");
        }
        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        $cars = $query->paginate(10);
        $brands = Car::select('brand')->with('category')->orderBy('id','DESC')->distinct()->pluck('brand');
        return view('cars.index', compact('cars', 'brands'));
    }

    public function exportExcel(Request $request)
{
    return Excel::download(new CarsExport($request), 'cars.xlsx');
}

public function exportPDF(Request $request)
{
    $query = Car::query();

    if ($request->filled('search')) {
        $query->where('registration_number', 'like', "%{$request->search}%")
              ->orWhere('model', 'like', "%{$request->search}%");
    }
    if ($request->filled('brand')) {
        $query->where('brand', $request->brand);
    }

    $cars = $query->get();

    $pdf = Pdf::loadView('cars.export_pdf', compact('cars'));
    return $pdf->download('cars.pdf');
}

    public function create()
    {
        $categories = CarCategory::all();
        return view('cars.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'registration_number' => 'required|string|unique:cars',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'color' => 'nullable|string',
            'category_id' => 'required',
            'mileage' => 'nullable|integer',
            'fuel_type' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('cars', 'public');
        }
        Car::create($data);

        return redirect()->route('cars.index')->with('success', 'Car added successfully!');
    }

    public function show(Car $car)
    {
        return view('cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        $categories = CarCategory::all();
        return view('cars.form', compact('car', 'categories'));
    }

    public function update(Request $request, Car $car)
    {
        $data = $request->validate([
            'registration_number' => 'required|string|unique:cars,registration_number,' . $car->id,
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'category_id' => 'required',
            'color' => 'nullable|string',
            'mileage' => 'nullable|integer',
            'fuel_type' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            if ($car->photo) {
                Storage::disk('public')->delete($car->photo);
            }
            $data['photo'] = $request->file('photo')->store('cars', 'public');
        }

        $car->update($data);

        return redirect()->route('cars.index')->with('success', 'Car updated successfully!');
    }

    public function destroy(Car $car)
    {
        if ($car->photo) {
            Storage::disk('public')->delete($car->photo);
        }
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'Car deleted successfully!');
    }
}
