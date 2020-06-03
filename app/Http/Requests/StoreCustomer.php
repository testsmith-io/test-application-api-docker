<?php

namespace App\Http\Requests;

class StoreCustomer extends BaseFormRequest
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
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:20',
            'company' => 'string|max:80',
            'address' => 'required|string|max:70',
            'city' => 'required|string|max:40',
            'state' => 'string|max:40',
            'country' => 'required|string|max:40',
            'postalcode' => 'string|max:10',
            'phone' => 'string|max:24',
            'fax' => 'string|max:24',
            'email' => 'required|string|max:60',
            'password' => 'required|string|max:255',
            'support_rep_id' => 'numeric|exists:employee,id'
        ];
    }
}
