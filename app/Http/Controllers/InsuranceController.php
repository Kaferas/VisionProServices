<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InsuranceController extends Controller
{
    public function index(Request $request)
    {
        $query = Insurance::with('car');

    if ($request->filled('search')) {
        $query->where('policy_number','like',"%{$request->search}%");
    }

    if ($request->filled('expiry_date')) {
        $query->whereDate('expiry_date','<=',$request->expiry_date);
    }

    $insurances = $query->paginate(10);

    return view('insurances.index',compact('insurances'));
    }

    public function create()
    {
        $cars = Car::all();
        return view('insurances.form', compact('cars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'provider' => 'required|string',
            'policy_number' => 'required|string',
            'cost' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'active' => 'boolean',
            'document_scan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($request->hasFile('document_scan')) {
            $data['document_scan'] = $request->file('document_scan')->store('insurances', 'public');
        }

        Insurance::create($data);

        return redirect()->route('insurances.index')->with('success', 'Insurance added successfully!');
    }

    public function show(Insurance $insurance)
    {
        return view('insurances.show', compact('insurance'));
    }

    public function edit(Insurance $insurance)
    {
        $cars = Car::all();
        return view('insurances.form', compact('insurance', 'cars'));
    }

    public function update(Request $request, Insurance $insurance)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'provider' => 'required|string',
            'policy_number' => 'required|string',
            'cost' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'active' => 'boolean',
            'document_scan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($request->hasFile('document_scan')) {
            if ($insurance->document_scan) {
                Storage::disk('public')->delete($insurance->document_scan);
            }
            $data['document_scan'] = $request->file('document_scan')->store('insurances', 'public');
        }

        $insurance->update($data);

        return redirect()->route('insurances.index')->with('success', 'Insurance updated successfully!');
    }

    public function destroy(Insurance $insurance)
    {
        if ($insurance->document_scan) {
            Storage::disk('public')->delete($insurance->document_scan);
        }
        $insurance->delete();
        return redirect()->route('insurances.index')->with('success', 'Insurance deleted successfully!');
    }
}
