<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $query = Product::query();

        // Filtrer par nom
        if ($request->search) {
            $query->where('item_name', 'like', "%$search%")
                ->orWhere('item_codebar', 'like', "%$search%");
        }
        if ($request->category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('category_id', $category);
            });
        }

        $products = $query->orderBy("item_id", "DESC")->get();
        $categories = Category::all();
        return view('product.index', [
            "products" => $products,
            "search" => $search,
            "category" => $category,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = new Product();
        $categories = Category::all();
        $unities = Unity::all();

        return view('product.add', [
            'product' => $product,
            'categories' => $categories,
            'unities' => $unities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $data['item_codebar'] = $this->generateCodeBar();
        $data['item_tva'] = $request->item_tva ?? 1;
        $data['item_tc'] = $request->item_tc ?? 0;
        $data['item_pf'] = $request->item_pf ?? 0;
        $data['item_costprice'] = $request->item_costprice ?? 0;
        $data['item_status'] = 0;
        $data['created_by'] = auth()->id();

        $product = Product::create($data);

        return redirect()
            ->route('product.index')->with(
                'success',
                "L'article a été enregistré avec succés"
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $categories = Category::all();
        $unities = Unity::all();

        return view('product.detail', [
            'product' => $product,
            'categories' => $categories,
            'unities' => $unities,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $unities = Unity::all();

        return view('product.edit', [
            'product' => $product,
            'categories' => $categories,
            'unities' => $unities,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $data['item_tva'] = $request->item_tva ?? 1;
        $data['item_tc'] = $request->item_tc ?? 0;
        $data['item_pf'] = $request->item_pf ?? 0;
        $data['item_costprice'] = $request->item_costprice ?? 0;
        $data['item_status'] = 0;
        $data['modified_by'] = auth()->id();

        $product = $product->update($data);

        return redirect()
            ->route('product.index')->with(
                'success',
                "L'article a été modifié avec succés"
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    private function generateCodeBar()
    {
        $prefix = 'PR';
        $month = Carbon::now()->format('m');
        $lastProduct = Product::where('item_codebar', 'like', $prefix . $month . '%')->orderBy('item_codebar', 'desc')->first();

        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->item_codebar, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . $month . $newNumber;
    }
}
