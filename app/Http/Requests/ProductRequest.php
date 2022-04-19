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
        if ($this->method() == 'POST'){
            return  [
                'name' => 'required|string',
                'description' => 'required|string',
                'quantity' => 'required|numeric|min:1',
                'price' => 'required|numeric|min:1',
                'seller_id' => 'numeric|exists:users,id',
                'category_id' => 'required|numeric|exists:categories,id',
                'image' => 'required|image|mimes:png,jpg,jpeg'
            ];
        }
        else
            return [
                'name' => 'string',
                'description' => 'string',
                'quantity' => 'numeric',
                'price' => 'numeric|min:1',
                'category_id' => 'numeric|exists:categories,id',
                'image' => 'image|mimes:png,jpg,jpeg'
            ];


//        $rules = [
//            'name' => 'required|string',
//            'description' => 'required|string',
//            'quantity' => 'required|numeric',
//            'price' => 'required|numeric|min:1',
//            'user_id' => 'numeric|exists:users,id',
//            'category_id' => 'required|numeric|exists:categories,id'
//        ];
//        if ($this->method() == 'POST') {
//            $rules['image'] = 'image|mimes:png,jpg,jpeg';
//        }
//
//        if ($this->method() == 'PUT' || $this->method() == 'PATCH') {
//            $rules = [
//                'name' => 'string',
//                'description' => 'string',
//                'quantity' => 'numeric',
//                'price' => 'numeric|min:1',
//                'user_id' => 'numeric|exists:users,id',
//                'category_id' => 'numeric|exists:categories,id'
//            ];
//        }
//
//        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.required' => 'The category field is required',
            'category_id.exists:categories,id' => 'The selected category doesn\'t exists',
        ];
    }
}
