<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        if (!empty($search)) {
            $categories = Category::query()->where('title', 'LIKE', "%{$search}%")->orderBy("category_id", "DESC")->get();
        } else {
            $categories = Category::orderBy("category_id", "DESC")->get();
        }

        $categories = collect($categories)->sortBy('title');

        return view('product.category', [
            "categories" => $categories,
            "search" => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required',
        ], [
            'title.required' => 'Le titre est requis pour continuer',
            'store_id.required' => 'La boutique est requis pour continuer',
        ]);

        if (!$validated->fails()) {

            $Category = Category::create([
                'title' => $request['title'],
                'categorie_has_bon' => $request['categorie_has_bon'] == 'on' ? 1 : 0,
                'created_by' => auth()->id(),
            ]);

            if ($Category) {
                echo json_encode(
                    [
                        'success' => true,
                        'messages' => 'Category de l\'article enregistré avec succés',
                        ]
                    );
            } else {
                echo json_encode(
                    [
                        'success' => false,
                        'messages' => ['Quelque chose a mal tourné,réessayez svp!!'],
                        ]
                    );
                }
            } else {
                echo json_encode(
                    [
                        'success' => false,
                        'messages' => $validated->errors(),
                        ]
                    );
                }
            }

            /**
             * Display the specified resource.
             */
            public function show(Category $category)
            {
                //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required',
        ], [
            'title.required' => 'Le titre est requis pour continuer',
        ]);

        if (!$validated->fails()) {
            // $data = $validated->safe()->only('title');

            $category = $category->update([
                'categorie_has_bon' => $request['categorie_has_bon']== 'on' ? 1 : 0,
                'title' => $request['title'],
            ]);

            if ($category) {
                echo json_encode(
                    [
                        'success' => true,
                        'messages' => 'Category de l\'article modifié avec succés',
                    ]
                );
            } else {
                echo json_encode(
                    [
                        'success' => false,
                        'messages' => ['Quelque chose a mal tourné,réessayez svp!!'],
                    ]
                );
            }
        } else {
            echo json_encode(
                [
                    'success' => false,
                    'messages' => $validated->errors(),
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        echo json_encode(
            [
                'success' => true,
                'messages' => 'Category de l\'article supprimé avec succés',
            ]
        );
    }
}
