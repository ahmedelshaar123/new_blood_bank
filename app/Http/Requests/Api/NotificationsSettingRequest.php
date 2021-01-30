<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class NotificationsSettingRequest extends FormRequest
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
            'governorate_id' => 'required|array',
            'governorate_id.*' => 'exists:governorates,id',
            'blood_type_id' => 'required|array',
            'blood_type_id.*' => 'exists:blood_types,id',
        ];
    }
}
