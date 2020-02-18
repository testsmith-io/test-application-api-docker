<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Album
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="AlbumRequest",
 *     type="object",
 *     title="AlbumRequest",
 *     required={"title", "artist_id"},
 *     properties={
 *         @OA\Property(property="title", type="string"),
 *         @OA\Property(property="artist_id", type="integer")
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="AlbumResponse",
 *     type="object",
 *     title="AlbumResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="title", type="string"),
 *         @OA\Property(property="artist_id", type="integer"),
 *         @OA\Property(property="created_at", type="string"),
 *         @OA\Property(property="artist", ref="#/components/schemas/ArtistResponse")
 *     }
 * )
 */

class Album extends BaseModel
{
    protected $table = 'album';
    protected $fillable = ['title', 'artist_id'];

    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }
}
