<?php

namespace App\Http\Controllers;

use App\Helpers\PosHelper;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockFlow;
use App\Models\Supply;
use App\Models\SupplyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $date = $request->query('date');

        $supplies = Supply::query();

        if ($request->has('status')) {
            $supplies->where('supply_status', '=', $status);
        }

        if ($request->has('date')) {
            $supplies->where('created_at', '>=', $date);
        }

        $supplies = $supplies->orderBy('supply_id', "DESC")->get();

        return view('supply.index', [
            'supplies' => $supplies,
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

        return view('supply.add', [
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
            "appro_title" => "required",
            "supply_type" => "required",
            "quantity" => "required|array",
            "quantity.*" => "gt:0",
        ], [
            "appro_title.required" => "Le titre de l'approvisionnement est obligatoire",
            "supply_type.required" => "Le Type d'entré est obligatoire",
            "quantity.required" => "Vous devez sélectionner au moins un produit",
            "quantity.*.gt" => "La quantité approvisionné doit etre superieur à 0,verifiez tous les champs",
        ]);

        if ($validated->fails()) {
            echo json_encode([
                'success' => false,
                'messages' => $validated->errors(),
            ]);
        } else {

            // dd($request->codebar);

            $title = $request->appro_title;
            $product = $request->product;
            $codebar = $request->codebar;
            $quantity = $request->quantity;
            $price = $request->price;
            $supply_code = $this->generateCodeBar();

            $status = Supply::create([
                "title" => $title,
                "supply_code" => $supply_code,
                "supply_status" => 0,
                "supply_type" => $request->supply_type,
                "created_by" => auth()->id(),
            ]);
            $now = Carbon::now()->toDateTimeString();

            if ($status) {
                $save_data = [];
                for ($i = 0; $i < sizeof($codebar); $i++) {
                    array_push($save_data, [
                        "product_name" => $product[$i],
                        "ref_product_code" => $codebar[$i],
                        "item_quantity" => $quantity[$i],
                        "purchase_price" => $price[$i],
                        "ref_supply_code" => $supply_code,
                        "supply_type" => $request->supply_type,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                }

                $save = SupplyDetail::insert($save_data);


                if ($save) {
                    echo json_encode([
                        'success' => true,
                        'messages' => "Approvisionnement enregistré avec succés",
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
    public function show(Supply $supply)
    {
        $supplyDetails = SupplyDetail::where('ref_supply_code', '=', $supply->supply_code)->get();

        return view('supply.view', [
            "supply" => $supply,
            "supplyDetails" => $supplyDetails,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supply $supply)
    {
        $supplyDetails = SupplyDetail::where('ref_supply_code', '=', $supply->supply_code)->get();
        $categories = Category::select('title', 'category_id')->get();
        $products = Product::select('item_id', 'item_codebar', 'item_name', 'item_costprice', 'item_category', 'item_quantity')->where('item_type', 0)->get();

        return view('supply.edit', [
            "categories" => $categories,
            "products" => $products,
            "supply" => $supply,
            "supplyDetails" => $supplyDetails,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supply $supply)
    {
        if ($request->type == 'update') {

            $validated = Validator::make($request->all(), [
                "appro_title" => "required",
                "supply_type" => "required",
                "quantity" => "required|array",
                "quantity.*" => "gt:0",
            ], [
                "appro_title.required" => "Le titre de l'approvisionnement est obligatoire",
                "supply_type.required" => "Le Type d'entré est obligatoire",
                "quantity.required" => "Vous devez sélectionner au moins un produit",
                "quantity.*.gt" => "La quantité approvisionné doit etre superieur à 0,verifiez tous les champs",
            ]);

            if ($validated->fails()) {
                echo json_encode([
                    'success' => false,
                    'messages' => $validated->errors(),
                ]);
            } else {

                // dd($request->codebar);

                $title = $request->appro_title;
                $product = $request->product;
                $codebar = $request->codebar;
                $quantity = $request->quantity;
                $price = $request->price;
                $supply_code = $supply->supply_code;

                $status = $supply->update([
                    "title" => $title,
                    "supply_type" => $request->supply_type,
                    "modified_by" => auth()->id(),
                ]);
                $now = Carbon::now()->toDateTimeString();

                if ($status) {
                    $save_data = [];
                    SupplyDetail::where('ref_supply_code', $supply_code)->delete();
                    for ($i = 0; $i < sizeof($codebar); $i++) {
                        array_push($save_data, [
                            "product_name" => $product[$i],
                            "ref_product_code" => $codebar[$i],
                            "item_quantity" => $quantity[$i],
                            "purchase_price" => $price[$i],
                            "ref_supply_code" => $supply_code,
                            "supply_type" => $request->supply_type,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                    }

                    $save = SupplyDetail::insert($save_data);


                    if ($save) {
                        echo json_encode([
                            'success' => true,
                            'messages' => "Approvisionnement Modifié avec succés",
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
            $status = $supply->update([
                "supply_status" => 1,
                "confirmed_or_rejected_by" => auth()->id(),
                "confirmed_or_rejected_at" => date('Y-m-d H:i:s')
            ]);

            $this->updateProductQty($supply->supply_code);
            // PosHelper::setEnv('PENDING_OBR_STOCK','true');
            if ($status) {
                echo json_encode([
                    'success' => true,
                    'messages' => "Approvisionnement approuvé avec succés",
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
    public function destroy(Request $request, Supply $supply)
    {
        try {
            if ($request->type == 'principal') {
                SupplyDetail::where('ref_supply_code', $supply->supply_code)->delete();
                $supply->delete();
            } else {
                SupplyDetail::where('id', $request->detail)->delete();
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
        $prefix = 'SP';
        $month = Carbon::now()->format('m');
        $lastsupply = Supply::where('supply_code', 'like', $prefix . $month . '%')->orderBy('supply_code', 'desc')->first();

        if ($lastsupply) {
            $lastNumber = (int) substr($lastsupply->supply_code, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . $month . $newNumber;
    }

    private function updateProductQty($code)
    {
        $data = SupplyDetail::where('ref_supply_code', $code)->get();
        $stockFlowData = [];
        foreach ($data as $key => $value) {
            $product = Product::where('item_codebar', $value->ref_product_code)->first();
            array_push($stockFlowData, [
                "mov_ref_code" => $code,
                "mov_ref_product_code" => $value->ref_product_code,
                "mov_type" => $value->supply_type,
                "mov_item_previous_qty" => $product->item_quantity ?? 0,
                "mov_item_quantity" => $value->item_quantity,
                "mov_item_price" => $value->purchase_price,
                "mov_created_by" => auth()->id(),
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
            $product->update([
                "item_quantity" => $product->item_quantity + $value->item_quantity
            ]);
        }

        StockFlow::insert($stockFlowData);
    }
}
