<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalInfoRequest extends FormRequest
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
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|regex:/^0[0-9]{9,10}$/|unique:customers',
            'id_number' => 'required|string|unique:customers',
            'birthday' => 'required|string',
            'gender' => 'nullable|in:0,1,null',
            'career' => 'required|string',
            'income' => 'required|string',
            'loan_purpose' => 'required|string',
            'relative_phone1' => 'required|string|regex:/^0[0-9]{9,10}$/',
            'relative_relationship1' => 'required|string',
            'relative_phone2' => 'required|string|regex:/^0[0-9]{9,10}$/',
            'relative_relationship2' => 'required|string',
            'captcha' => 'required|captcha',
        ];
    }
}
