<?php

namespace App\Http\Requests;

class StoreTrack extends BaseFormRequest
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
            'name' => 'required|string|max:200',
            'album_id'=> 'required|numeric|exists:album,id',
            'mediatype_id' => 'required|numeric|exists:mediatype,id',
            'genre_id' => 'required|numeric|exists:genre,id',
            'milliseconds' => 'required|numeric',
            'bytes' => 'required|numeric',
            'unit_price' => 'required'
        ];
    }
}
