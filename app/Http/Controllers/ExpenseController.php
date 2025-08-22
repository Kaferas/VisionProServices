<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Car;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('car');

        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $expenses = $query->paginate(10);
        $types = Expense::select('type')->distinct()->pluck('type');

        return view('expenses.index', compact('expenses', 'types'));
    }

    public function create()
    {
        $cars = Car::all();
        return view('expenses.form', compact('cars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'type' => 'required|in:carburant,entretien,assurance,réparation,taxe,autre',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'invoice_number' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully!');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $cars = Car::all();
        return view('expenses.form', compact('expense', 'cars'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'type' => 'required|in:carburant,entretien,assurance,réparation,taxe,autre',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'invoice_number' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }
}
