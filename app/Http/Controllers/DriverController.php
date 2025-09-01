<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query();

        if ($request->filled('search')) {
            $query->where('name','like',"%{$request->search}%")
                  ->orWhere('license_number','like',"%{$request->search}%")
                  ->orWhere('cni_driver','like',"%{$request->search}%"); // Recherche par CNI
        }

        if ($request->filled('license_expiry')) {
            $query->whereDate('license_expiry','<=',$request->license_expiry);
        }

        $drivers = $query->paginate(10);

        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        $cars = Car::all();
        return view('drivers.form', compact('cars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'cni_driver' => 'nullable|string', // Ajout du champ CNI
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'assigned_car_id' => 'nullable|exists:cars,id',
        ]);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('drivers', 'public');
        }

        Driver::create($data);

        return redirect()->route('drivers.index')->with('success', 'Driver added successfully!');
    }

    public function show(Driver $driver)
    {
        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        $cars = Car::all();
        return view('drivers.form', compact('driver', 'cars'));
    }

    public function update(Request $request, Driver $driver)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'cni_driver' => 'nullable|string', // Ajout du champ CNI
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'assigned_car_id' => 'nullable|exists:cars,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($driver->photo) {
                Storage::disk('public')->delete($driver->photo);
            }
            $data['photo'] = $request->file('photo')->store('drivers', 'public');
        }

        $driver->update($data);

        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully!');
    }

    public function destroy(Driver $driver)
    {
        if ($driver->photo) {
            Storage::disk('public')->delete($driver->photo);
        }
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully!');
    }
}
