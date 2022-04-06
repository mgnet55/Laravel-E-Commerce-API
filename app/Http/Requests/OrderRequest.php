<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
                'city_id'=>'required|exists:cities,id',
                'street'=>'required|string',
                'status'=>'in:Processing,On way,Done',
                'shipping_id'=>'numeric|exists:shipping_companies,id',
                'notes'=>'string'
        ];
    }
}
