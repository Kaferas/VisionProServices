<?php

namespace App\Http\Controllers;

use App\Helpers\PosHelper;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sortie;
use App\Models\SortieDetail;
use App\Models\StockFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class SortieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $date = $request->query('date');

        $query = Sortie::query();

        if ($request->has('status')) {
            $query->where('sortie_status', '=', $status);
        }

        if ($request->has('date')) {
            $query->where('created_at', '>=', $date);
        }

        $sorties = $query->orderBy("sortie_id", "DESC")->get();

        return view('sortie.index', [
            'sorties' => $sorties,
            'status' => $status,
            'date' => $date,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('title', 'category_id')->get();
        $products = Product::select('item_id', 'item_codebar', 'item_name', 'item_costprice', 'item_category', 'item_quantity')->where('item_type', 0)->get();

        return view('sortie.add', [
            "categories" => $categories,
            "products" => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            "sortie_title" => "required",
            "sortie_type" => "required",
            "quantity" => "required|array",
            "quantity.*" => "gt:0",
        ], [
            "sortie_title.required" => "Le titre de la sortie est obligatoire",
            "sortie_type.required" => "Le Type de sortie est obligatoire",
            "quantity.required" => "Vous devez sélectionner au moins un produit",
            "quantity.*.gt" => "La quantité a retiré doit etre superieur à 0,verifiez tous les champs",
        ]);

        if ($validated->fails()) {
            echo json_encode([
                'success' => false,
                'messages' => $validated->errors(),
            ]);
        } else {

            // dd($request->codebar);

            $title = $request->sortie_title;
            $product = $request->product;
            $codebar = $request->codebar;
            $quantity = $request->quantity;
            $price = $request->price;
            $sortie_code = $this->generateCodeBar();

            $status = Sortie::create([
                "sortie_title" => $title,
                "sortie_code" => $sortie_code,
                "sortie_status" => 0,
                "sortie_type" => $request->sortie_type,
                "created_by" => auth()->id(),
            ]);
            $now = Carbon::now()->toDateTimeString();

            if ($status) {
                $save_data = [];
                for ($i = 0; $i < sizeof($codebar); $i++) {
                    array_push($save_data, [
                        "item_sortie_name" => $product[$i],
                        "ref_product_code" => $codebar[$i],
                        "item_sortie_quantity" => $quantity[$i],
                        "item_sortie_price" => $price[$i],
                        "ref_sortie_code" => $sortie_code,
                        "sortie_type" => $request->supply_type,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                }

                $save = SortieDetail::insert($save_data);


                if ($save) {
                    echo json_encode([
                        'success' => true,
                        'messages' => "Sortie enregistré avec succés",
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'messages' => ["Il y a eu une erreur,veillez réessayer svp!!"],
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'messages' => ["Il y a eu une erreur,veillez réessayer svp!!"],
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sortie $sortie)
    {
        $sortieDetails = SortieDetail::where('ref_sortie_code', '=', $sortie->sortie_code)->get();

        return view('sortie.sortie', [
            "sortie" => $sortie,
            "sortieDetails" => $sortieDetails,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sortie $sortie)
    {
        $sortieDetails = SortieDetail::where('ref_sortie_code', '=', $sortie->sortie_code)->get();
        $categories = Category::select('title', 'category_id')->get();
        $products = Product::select('item_id', 'item_codebar', 'item_name', 'item_costprice', 'item_category', 'item_quantity')->where('item_type', 0)->get();

        return view('sortie.edit', [
            "categories" => $categories,
            "products" => $products,
            "sortie" => $sortie,
            "sortieDetails" => $sortieDetails,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sortie $sortie)
    {
        if ($request->type == 'update') {
            $validated = Validator::make($request->all(), [
                "sortie_title" => "required",
                "sortie_type" => "required",
                "quantity" => "required|array",
                "quantity.*" => "gt:0",
            ], [
                "sortie_title.required" => "Le titre de la sortie est obligatoire",
                "sortie_type.required" => "Le Type de sortie est obligatoire",
                "quantity.required" => "Vous devez sélectionner au moins un produit",
                "quantity.*.gt" => "La quantité a retiré doit etre superieur à 0,verifiez tous les champs",
            ]);

            if ($validated->fails()) {
                echo json_encode([
                    'success' => false,
                    'messages' => $validated->errors(),
                ]);
            } else {

                // dd($request->codebar);

                $title = $request->sortie_title;
                $product = $request->product;
                $codebar = $request->codebar;
                $quantity = $request->quantity;
                $price = $request->price;
                $sortie_code = $sortie->sortie_code;

                $status = $sortie->update([
                    "sortie_title" => $title,
                    "sortie_type" => $request->sortie_type,
                    "modified_by" => auth()->id(),
                ]);
                $now = Carbon::now()->toDateTimeString();

                if ($status) {
                    $save_data = [];
                    SortieDetail::where('ref_sortie_code', '=', $sortie->sortie_code)->delete();
                    for ($i = 0; $i < sizeof($codebar); $i++) {
                        array_push($save_data, [
                            "item_sortie_name" => $product[$i],
                            "ref_product_code" => $codebar[$i],
                            "item_sortie_quantity" => $quantity[$i],
                            "item_sortie_price" => $price[$i],
                            "ref_sortie_code" => $sortie_code,
                            "sortie_type" => $request->sortie_type,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                    }

                    $save = SortieDetail::insert($save_data);


                    if ($save) {
                        echo json_encode([
                            'success' => true,
                            'messages' => "Sortie modifié avec succés",
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'messages' => ["Il y a eu une erreur,veillez réessayer svp!!"],
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'messages' => ["Il y a eu une erreur,veillez réessayer svp!!"],
                    ]);
                }
            }
        } else {
            $status = $sortie->update([
                "sortie_status" => 1,
                "confirmed_by" => auth()->id(),
                "confirmed_at" => date('Y-m-d H:i:s')
            ]);

            $this->updateProductQty($sortie->sortie_code);

            // PosHelper::setEnv('PENDING_OBR_STOCK','true');

            if ($status) {
                echo json_encode([
                    'success' => true,
                    'messages' => "Sortie approuvé avec succés",
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'messages' => ["Il y a eu une erreur,veillez réessayer svp!!"],
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Sortie $sortie)
    {
        try {
            if ($request->type == 'principal') {
                SortieDetail::where('ref_sortie_code', '=', $sortie->sortie_code)->delete();
                $sortie->delete();
            } else {
                SortieDetail::where('id', $request->detail)->delete();
            }

            echo json_encode([
                "success" => true,
                "messages" => "Données supprimé avec succés",
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                "success" => false,
                "messages" => $th,
            ]);
        }
    }

    private function generateCodeBar()
    {
        $prefix = 'SRT';
        $month = Carbon::now()->format('m');
        $lastData = Sortie::where('sortie_code', 'like', $prefix . $month . '%')->orderBy('sortie_code', 'desc')->first();

        if ($lastData) {
            $lastNumber = (int) substr($lastData->sortie_code, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . $month . $newNumber;
    }

    private function updateProductQty($code)
    {
        $data = SortieDetail::where('ref_sortie_code', $code)->get();
        $stockFlowData = [];
        foreach ($data as $key => $value) {
            $product = Product::where('item_codebar', $value->ref_product_code)->first();

            if ($product->item_quantity == 0 || $product->item_quantity < $value->item_sortie_quantity) {
                $newQty = 0;
                $diff = $value->item_sortie_quantity - $product->item_quantity;
                $flowQty = $value->item_sortie_quantity - $diff;
            } else {
                $newQty = $product->item_quantity - $value->item_sortie_quantity;
                $flowQty = $value->item_sortie_quantity;
            }

            array_push($stockFlowData, [
                "mov_ref_code" => $code,
                "mov_ref_product_code" => $value->ref_product_code,
                "mov_type" => $value->sortie_type,
                "mov_item_previous_qty" => $product->item_quantity ?? 0,
                "mov_item_quantity" => $flowQty,
                "mov_item_price" => $value->item_sortie_price,
                "mov_created_by" => auth()->id(),
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);

            $product->update([
                "item_quantity" => $newQty
            ]);
        }

        StockFlow::insert($stockFlowData);
    }
}
