<?php

namespace App\Http\Requests;

class StoreGenre extends BaseFormRequest
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
        switch($this->method()) {
            case 'POST':
                {
                    return [
                        'name' => 'required|string|max:120'
                    ];
                }
        }

    }
}
