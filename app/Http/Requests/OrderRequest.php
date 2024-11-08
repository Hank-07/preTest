<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|string',
            'name' => 'required|string',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|string',
            'currency' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => "The 'id' field is required.",
            'id.string' => "The 'id' field must be a string.",
            'name.required' => "The 'name' field is required.",
            'name.string' => "The 'name' field must be a string.",
            'address.required' => "The 'address' field is required.",
            'address.array' => "The 'address' field must be an array (object).",
            'address.city.required' => "The 'city' field in 'address' is required.",
            'address.district.required' => "The 'district' field in 'address' is required.",
            'address.street.required' => "The 'street' field in 'address' is required.",
            'price.required' => "The 'price' field is required.",
            'price.numeric' => "The 'price' field must be a number.",
            'currency.required' => "The 'currency' field is required.",
            'currency.string' => "The 'id' field must be a string.",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors();
        throw new HttpResponseException(response()->json(['status' => false, 'error' => $message->first()], 400));
    }
}
