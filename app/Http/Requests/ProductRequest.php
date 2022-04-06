<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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

        $rules = [
            'name'=>'required|string',
            'description'=>'required|string',
            'quantity'=>'required|numeric',
            'price'=>'required|float',
            'user_id'=>'required|numeric|exists:users,id',
            'cat_id'=>'required|numeric|exists:categories,id'
        ];
        if($this->method() == 'POST')
        {
           $rules['image'] = 'required|image|mime:png,jpg,jpeg';
        }

        return $rules;

    }
}
