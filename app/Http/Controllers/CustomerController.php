<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');

        if (!empty($search)) {
            $allCustomers = Customer::query()
                ->active()->where('customer_name', 'LIKE', "%{$search}%")->orderBy('customer_id', "DESC")->get();
        } else {
            $allCustomers = Customer::active()->orderBy('customer_id', "DESC")->get();
        }

        return view('customer.index', [
            'allCustomers' => $allCustomers,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $loginData = Company::select('username', 'password')->first();
        // $loginData = new stdClass();
        // $loginData->username = $company->username;
        // $loginData->password = $company->password;

        return view('customer.add', [
            'loginData' => $loginData,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $validated = $request->safe()->all();

        $path = "";
        if ($request->hasFile('customer_filepath')) {
            $file = $request->file('customer_filepath');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs(
                'customer_documents/' . $validated["customer_name"],
                $filename,
                'public'
            );
        }

        $second_path = "";
        if ($request->hasFile('second_customer_filepath')) {
            $file = $request->file('second_customer_filepath');
            $filename = 'second_'.time() . '_' . $file->getClientOriginalName();
            $second_path = $file->storeAs(
                'customer_documents/' . $validated["customer_name"],
                $filename,
                'public'
            );
        }

        $customer = Customer::create([
            "customer_name" => $validated['customer_name'],
            "customer_address" => $validated['customer_address'],
            "customer_phone" => $request->customer_phone ?? "",
            "customer_type" => $validated['customer_type'],
            "customer_tin" => $request->customer_tin,
            "discount" => $request->discount ?? 0,
            "customer_vatpayer" => $request->customer_vatpayer,
            "customer_id_number" => $request->customer_id_number,
            "customer_gender" => $request->customer_gender,
            "customer_filepath" => $path,
            "second_customer_name" => $request->second_customer_name,
            "second_customer_id_number" => $request->second_customer_id_number,
            "second_customer_gender" => $request->second_customer_gender,
            "second_customer_filepath" => $second_path,
            "created_by" => auth()->id()
        ]);

        return redirect()
            ->route('customer.index')->with(
                'success',
                'Le client a été enregistré avec succéss'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $loginData = Company::select('username', 'password')->first();
        return view('customer.edit', [
            "customer" => $customer,
            "loginData" => $loginData,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $validated = $request->safe()->all();

        $path = "";
        if ($request->hasFile('customer_filepath')) {
            $file = $request->file('customer_filepath');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs(
                'customer_documents/' . $customer->customer_id,
                $filename,
                'public'
            );
        }

        $second_path = "";
        if ($request->hasFile('second_customer_filepath')) {
            $file = $request->file('second_customer_filepath');
            $filename = 'second_'.time() . '_' . $file->getClientOriginalName();
            $second_path = $file->storeAs(
                'customer_documents/' . $customer->customer_id,
                $filename,
                'public'
            );
        }

        $customer->update([
            "customer_name" => $validated['customer_name'],
            "customer_address" => $validated['customer_address'],
            "customer_phone" => $request->customer_phone ?? "",
            "customer_type" => $validated['customer_type'],
            "customer_tin" => $request->customer_tin,
            "discount" => $request->discount ?? 0,
            "customer_vatpayer" => $request->customer_vatpayer,
            "customer_id_number" => $request->customer_id_number,
            "customer_gender" => $request->customer_gender,
            "customer_filepath" => $path,
            "second_customer_name" => $request->second_customer_name,
            "second_customer_id_number" => $request->second_customer_id_number,
            "second_customer_gender" => $request->second_customer_gender,
            "second_customer_filepath" => $second_path,
            "created_by" => auth()->id()
        ]);

        return redirect()
            ->route('customer.index')->with(
                'success',
                'Le client a été modifié avec succés'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->update([
            "customer_status" => 1,
        ]);

        echo json_encode([
            'success' => true,
            "messages" => "Clients supprimé avec succés"
        ]);
    }

    public function createCustomer(Request $request)
    {
        $customer = Customer::create([
            "customer_name" => $request->member_name,
            "customer_address" => $request->member_address,
            "customer_phone" => $request->member_phone ?? "",
            "customer_type" => 1,
            "customer_is_member" => 1,
            "customer_vatpayer" => 0,
            "created_by" => 1
        ]);

        if (!$customer) {
            return response()->json([
            'success' => false,
            'status' => 500,
            'message' => 'Une erreur est survenue lors de la création du client'
            ], 500);
        }
        // Return a JSON response
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Client créé avec succès',
            'customer_id' => $customer->customer_id,
            'customer_name' => $customer->customer_name
        ], 200);
    }

}
