<?php

namespace App\Http\Requests;

class UpdateCustomer extends BaseFormRequest
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
            'firstname' => 'sometimes|string|max:40',
            'lastname' => 'sometimes|string|max:20',
            'company' => 'string|max:80',
            'address' => 'sometimes|string|max:70',
            'city' => 'sometimes|string|max:40',
            'state' => 'string|max:40',
            'country' => 'sometimes|string|max:40',
            'postalcode' => 'string|max:10',
            'phone' => 'string|max:24',
            'fax' => 'string|max:24',
            'email' => 'sometimes|string|max:60',
            'support_rep_id' => 'numeric|exists:employee,id'
        ];
    }
}
