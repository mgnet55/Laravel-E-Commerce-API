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
        return true;
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
            'price'=>'required|regex:/^\d{1,13}(\.\d{1,4})?$/',
            'user_id'=>'required|numeric|exists:users,id',
            'category_id'=>'required|numeric|exists:categories,id'
        ];
        if($this->method() == 'POST')
        {
           $rules['image'] = 'required|image|mimes:png,jpg,jpeg';
        }

        return $rules;

    }
}
