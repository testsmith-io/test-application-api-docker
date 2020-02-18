<?php

namespace App\Http\Requests;

class StoreEmployee extends BaseFormRequest
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
            'firstname' => 'required|string|max:20',
            'lastname' => 'required|string|max:20',
            'title' => 'required|string|max:30',
            'birthdate' => 'required|date',
            'hiredate' => 'required|date',
            'address' => 'required|string|max:70',
            'city' => 'required|string|max:40',
            'state' => 'required|string|max:40',
            'country' => 'required|string|max:40',
            'postalcode' => 'required|string|max:10',
            'phone' => 'required|string|max:24',
            'fax' => 'required|string|max:24',
            'email' => 'required|string|max:60'
        ];
    }
}
