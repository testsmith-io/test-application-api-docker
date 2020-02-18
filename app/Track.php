<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Track
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="TrackRequest",
 *     type="object",
 *     title="TrackRequest",
 *     required={"title", "body"},
 *     properties={
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="composer", type="string"),
 *         @OA\Property(property="album_id", type="integer"),
 *         @OA\Property(property="mediatype_id", type="integer"),
 *         @OA\Property(property="genre_id", type="integer"),
 *         @OA\Property(property="milliseconds", type="integer"),
 *         @OA\Property(property="bytes", type="integer"),
 *         @OA\Property(property="unit_price", type="string"),
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="TrackResponse",
 *     type="object",
 *     title="TrackResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="composer", type="string"),
 *         @OA\Property(property="album_id", type="integer"),
 *         @OA\Property(property="mediatype_id", type="integer"),
 *         @OA\Property(property="genre_id", type="integer"),
 *         @OA\Property(property="milliseconds", type="integer"),
 *         @OA\Property(property="bytes", type="integer"),
 *         @OA\Property(property="unit_price", type="string"),
 *         @OA\Property(property="created_at", type="string")
 *     }
 * )
 */

class Track extends Model
{
    protected $table = 'track';
    protected $fillable = ['name', 'composer', 'album_id', 'mediatype_id', 'genre_id', 'milliseconds', 'bytes', 'unit_price'];

    public function album()
    {
        return $this->belongsTo('App\Album');
    }

    public function mediatype()
    {
        return $this->belongsTo('App\Mediatype');
    }

    public function genre()
    {
        return $this->belongsTo('App\Genre');
    }
}
