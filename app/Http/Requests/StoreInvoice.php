<?php

namespace App\Http\Requests;

class StoreInvoice extends BaseFormRequest
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
            'customer_id' => 'required|numeric|exists:customer,id',
            'invoice_date' => 'date_format:Y-m-d',
            'billing_address' => 'required|string|max:70',
            'billing_city' => 'required|string|max:40',
            'billing_state' => 'string|max:40',
            'billing_country' => 'required|string|max:40',
            'billing_postalcode' => 'string|max:10',
            'total' => 'required|numeric'
        ];
    }
}
