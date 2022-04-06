<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|unique:shipping_companies,name',
            'user_id'=>'required|numeric|exists:users,id',
            'phone'=>'required|numeric',
            'city_id'=>'required|numeric|exists:cities,id',
            'address_street'=>'required|string',
        ];
    }
}
