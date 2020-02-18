<?php

namespace App\Http\Requests;

class DestroyAlbum extends BaseFormRequest
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
            'id' => 'required|exists:album,id',
        ];
    }

    /**
     * Use route parameters for validation
     * @return array
     */
    public function validationData()
    {
        return app('request')->route()[2];
    }
}
