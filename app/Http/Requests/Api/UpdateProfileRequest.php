<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name' => 'required|max:25',
            'email' => Rule::unique('clients', 'email')->ignore($request->user()->id)
                    ,'required|email',
            'date_of_birth' => 'required|date',
            'last_date_of_donation' =>'required|date',
            'phone' => Rule::unique('clients', 'phone')->ignore($request->user()->id)
                    ,'required|digits_between:10,13',
            'password' =>'nullable|min:6',
            'password_confirmation' =>'nullable|same:password',
            'governorate_id' =>'required|exists:governorates,id',
            'city_id' =>'required|exists:cities,id',
            'blood_type_id' =>'required|exists:blood_types,id',
        ];
    }
}
