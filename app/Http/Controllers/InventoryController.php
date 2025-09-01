<?php

namespace App\Http\Controllers;

use App\Helpers\PosHelper;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\Product;
use App\Models\StockFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $date = $request->query('date');

        $query = Inventory::query();

        if ($request->has('status')) {
            $query->where('inventory_status', '=', $status);
        }

        if ($request->has('date')) {
            $query->where('created_at', '>=', $date);
        }

        $inventories = $query->orderBy("inventory_id", "DESC")->get();

        return view('inventory.index', [
            "inventories" => $inventories,
            "status" => $status,
            "date" => $date,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('title', 'category_id')->get();
        $products = Product::select('item_id', 'item_codebar', 'item_name', 'item_costprice', 'item_category', 'item_quantity')->where('item_type', 0)->get();

        return view('inventory.add', [
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
            "inventory_title" => "required",
            "p_quantity" => "required|array",
            "p_quantity.*" => "gte:0",
        ], [
            "inventory_title.required" => "Le titre de l'inventaire est obligatoire",
            "p_quantity.required" => "Vous devez sélectionner au moins un produit",
            "p_quantity.*.gte" => "La quantité physique doit etre superieur ou égal à 0,verifiez tous les champs",
        ]);

        if ($validated->fails()) {
            echo json_encode([
                'success' => false,
                'messages' => $validated->errors(),
            ]);
        } else {

            $title = $request->inventory_title;
            $product = $request->product;
            $codebar = $request->codebar;
            $s_quantity = $request->s_quantity; //qty in stystem
            $p_quantity = $request->p_quantity; //qty physic
            $price = $request->price;
            $code = $this->generateCodeBar();

            $status = Inventory::create([
                "inventory_title" => $title,
                "inventory_code" => $code,
                "inventory_status" => 0,
                "created_by" => auth()->id(),
            ]);
            $now = Carbon::now()->toDateTimeString();

            if ($status) {
                $save_data = [];
                for ($i = 0; $i < sizeof($codebar); $i++) {
                    array_push($save_data, [
                        "item_inventory_name" => $product[$i],
                        "ref_product_code" => $codebar[$i],
                        "item_system_quantity" => $s_quantity[$i],
                        "item_physic_quantity" => $p_quantity[$i],
                        "item_inventory_price" => $price[$i],
                        "ref_inventory_code" => $code,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                }

                $save = InventoryDetail::insert($save_data);


                if ($save) {
                    echo json_encode([
                        'success' => true,
                        'messages' => "Inventaire enregistré avec succés",
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
    public function show(Inventory $inventory)
    {
        $inventoryDetails = InventoryDetail::where('ref_inventory_code', $inventory->inventory_code)->get();

        return view('inventory.inventory', [
            "inventory" => $inventory,
            "inventoryDetails" => $inventoryDetails,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $inventoryDetails = InventoryDetail::where('ref_inventory_code', $inventory->inventory_code)->get();
        $categories = Category::select('title', 'category_id')->get();
        $products = Product::select('item_id', 'item_codebar', 'item_name', 'item_costprice', 'item_category', 'item_quantity')->where('item_type', 0)->get();

        return view('inventory.edit', [
            "inventory" => $inventory,
            "inventoryDetails" => $inventoryDetails,
            "categories" => $categories,
            "products" => $products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        if ($request->type == 'update') {
            $validated = Validator::make($request->all(), [
                "inventory_title" => "required",
                "p_quantity" => "required|array",
                "p_quantity.*" => "gte:0",
            ], [
                "inventory_title.required" => "Le titre de l'inventaire est obligatoire",
                "p_quantity.required" => "Vous devez sélectionner au moins un produit",
                "p_quantity.*.gte" => "La quantité physique doit etre superieur ou égal à 0,verifiez tous les champs",
            ]);

            if ($validated->fails()) {
                echo json_encode([
                    'success' => false,
                    'messages' => $validated->errors(),
                ]);
            } else {

                $title = $request->inventory_title;
                $product = $request->product;
                $codebar = $request->codebar;
                $s_quantity = $request->s_quantity; //qty in stystem
                $p_quantity = $request->p_quantity; //qty physic
                $price = $request->price;
                $code = $inventory->inventory_code;

                $status = $inventory->update([
                    "inventory_title" => $title,
                    "modified_by" => auth()->id(),
                ]);
                $now = Carbon::now()->toDateTimeString();

                if ($status) {
                    $save_data = [];
                    InventoryDetail::where('ref_inventory_code', $inventory->inventory_code)->delete();
                    for ($i = 0; $i < sizeof($codebar); $i++) {
                        array_push($save_data, [
                            "item_inventory_name" => $product[$i],
                            "ref_product_code" => $codebar[$i],
                            "item_system_quantity" => $s_quantity[$i],
                            "item_physic_quantity" => $p_quantity[$i],
                            "item_inventory_price" => $price[$i],
                            "ref_inventory_code" => $code,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                    }

                    $save = InventoryDetail::insert($save_data);


                    if ($save) {
                        echo json_encode([
                            'success' => true,
                            'messages' => "Inventaire Modifié avec succés",
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
            $status = $inventory->update([
                "inventory_status" => 1,
                "confirmed_by" => auth()->id(),
                "confirmed_at" => date('Y-m-d H:i:s')
            ]);

            $this->updateProductQty($inventory->inventory_code);
            // PosHelper::setEnv('PENDING_OBR_STOCK','true');

            if ($status) {
                echo json_encode([
                    'success' => true,
                    'messages' => "Inventaire approuvé avec succés",
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
    public function destroy(Request $request, Inventory $inventory)
    {
        try {
            if ($request->type == 'principal') {
                InventoryDetail::where('ref_inventory_code', $inventory->inventory_code)->delete();
                $inventory->delete();
            } else {
                InventoryDetail::where('id', $request->detail)->delete();
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
        $prefix = 'IVT';
        $month = Carbon::now()->format('m');
        $lastData = Inventory::where('inventory_code', 'like', $prefix . $month . '%')->orderBy('inventory_code', 'desc')->first();

        if ($lastData) {
            $lastNumber = (int) substr($lastData->inventory_code, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . $month . $newNumber;
    }

    private function updateProductQty($code)
    {
        $data = InventoryDetail::where('ref_inventory_code', $code)->get();
        $stockFlowData = [];
        foreach ($data as $key => $value) {
            $product = Product::where('item_codebar', $value->ref_product_code)->first();

            $flowQty = $value->item_physic_quantity;

            array_push($stockFlowData, [
                "mov_ref_code" => $code,
                "mov_ref_product_code" => $value->ref_product_code,
                "mov_type" => 'EI',
                "mov_item_previous_qty" => $product->item_quantity ?? 0,
                "mov_item_quantity" => $flowQty,
                "mov_item_price" => $value->item_inventory_price,
                "mov_created_by" => auth()->id(),
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);

            $product->update([
                "item_quantity" => $flowQty
            ]);
        }

        StockFlow::insert($stockFlowData);
    }
}
