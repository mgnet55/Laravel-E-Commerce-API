<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        return [

            'name' => 'required|alpha_dash',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'avatar' => 'image|mimes:png,jpg,jpeg',
            'address' => 'required|string',
            'city_id' => 'required|numeric|exists:cities,id',
            'phone' => 'required|numeric'
        ];}


        /**
         * Get the error messages for the defined validation rules.
         *
         * @return array
         */
        public function messages()
    {
        return [
            'city_id.required' => 'The city field is required',
            'city_id.exists:cities,id' => 'The city doesn\'t exists',
        ];
    }



}
