<?php


namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Urameshibr\Requests\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
