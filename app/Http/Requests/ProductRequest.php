<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "item_name" => "required",
            "item_sellprice" => "required",
            "item_corprice" => "required",
            "item_type" => "required",
            "item_isSellable" => "required",
            "item_category" => "required",
            "item_unity" => "required",
        ];
    }

    public function messages()
    {
        return [
            "item_name.required" => "Ce champs est obligatoire",
            "item_sellprice.required" => "Ce champs est obligatoire",
            "item_corprice.required" => "Ce champs est obligatoire",
            "item_type.required" => "Ce champs est obligatoire",
            "item_isSellable.required" => "Ce champs est obligatoire",
            "item_category.required" => "Ce champs est obligatoire",
            "item_unity.required" => "Ce champs est obligatoire",
        ];
    }
}
