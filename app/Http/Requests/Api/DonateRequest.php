<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DonateRequest extends FormRequest
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
            'age' => 'required|digits_between:1,3',
            'blood_type_id' => 'required|exists:blood_types,id',
            'bags_num' => 'required|numeric|min:1',
            'hos_name' => 'required|max:50',
            'hos_address' => 'required|max:75',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'city_id' => 'required|exists:cities,id',
            'phone' => 'required|digits_between:10,13',
        ];
    }
}
