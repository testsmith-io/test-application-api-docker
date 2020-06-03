<?php

namespace App\Http\Requests;

class StoreInvoiceline extends BaseFormRequest
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
            'invoice_id' => 'required|numeric|exists:invoice,id',
            'track_id' => 'required|numeric|exists:track,id',
            'unit_price' => 'required',
            'quantity' => 'required'
        ];
    }
}
