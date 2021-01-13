<?php

namespace App\Http\Requests\Api;

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
            'name' => 'required|max:25',
            'email' => 'required|email|unique:clients',
            'date_of_birth' => 'required|date',
            'last_date_of_donation' =>'required|date',
            'phone' =>'required|digits_between:10,13|unique:clients',
            'password' =>'required|min:6',
            'password_confirmation' =>'required|same:password',
            'city_id' =>'required|exists:cities,id',
            'blood_type_id' =>'required|exists:blood_types,id',
        ];
    }
}
