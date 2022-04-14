<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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

            'name' => 'alpha_dash',
            'email' => 'email',
            'password' => 'confirmed',
            'avatar' => 'image|mimes:png,jpg,jpeg',
            'address' => 'string',
            'city_id' => 'exists:cities,id',
            'phone' => 'numeric'
        ];
    }
}
