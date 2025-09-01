<?php

namespace App\Http\Controllers;

use App\Models\Unity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        if (!empty($search)) {
            $unities = Unity::query()->where('title', 'LIKE', "%{$search}%")->get();
        } else {
            $unities = Unity::all();
        }

        $unities = collect($unities)->sortBy('title');

        return view('product.unity', [
            "unities" => $unities,
            "search" => $search,
        ]);
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
        $validated = Validator::make($request->all(), [
            'title' => 'required'
        ], [
            'title.required' => 'Le titre est requis pour continuer'
        ]);

        if (!$validated->fails()) {
            $data = $validated->safe()->only('title');

            $unity = Unity::create([
                'title' => $data['title'],
                'created_by' => auth()->id(),
            ]);

            if ($unity) {
                echo json_encode(
                    [
                        'success' => true,
                        'messages' => 'Unité de mesure enregistré avec succés',
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
    public function show(Unity $unity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unity $unity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unity $unity)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required'
        ], [
            'title.required' => 'Le titre est requis pour continuer'
        ]);

        if (!$validated->fails()) {
            $data = $validated->safe()->only('title');

            $unity = $unity->update([
                'title' => $data['title'],
            ]);

            if ($unity) {
                echo json_encode(
                    [
                        'success' => true,
                        'messages' => 'Unité de mesure modifié avec succés',
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
    public function destroy(Unity $unity)
    {
        $unity->delete();

        echo json_encode(
            [
                'success' => true,
                'messages' => 'Unité de mesure supprimé avec succés',
            ]
        );
    }
}
