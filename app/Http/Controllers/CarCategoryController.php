<?php
namespace App\Http\Controllers;

use App\Models\CarCategory;
use Illuminate\Http\Request;

class CarCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $categories = CarCategory::when($search, function($query, $search) {
            $query->where('title', 'like', "%$search%");
        })->paginate(10);

        return view('car_categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('car_categories.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        CarCategory::create($request->all());

        return redirect()->route('car_categories.index')
                         ->with('success','Catégorie ajoutée avec succès');
    }

    public function show(CarCategory $car_category)
    {
        return view('car_categories.show', compact('car_category'));
    }

    public function edit(CarCategory $car_category)
    {
        return view('car_categories.form', compact('car_category'));
    }

    public function update(Request $request, CarCategory $car_category)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $car_category->update($request->all());

        return redirect()->route('car_categories.index')
                         ->with('success','Catégorie modifiée avec succès');
    }

    public function destroy(CarCategory $car_category)
    {
        $car_category->delete();

        return response()->json([
            'success' => true,
            'messages' => 'Catégorie supprimée avec succès'
        ]);
    }
}
