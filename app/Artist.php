<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Artist
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="ArtistRequest",
 *     type="object",
 *     title="ArtistRequest",
 *     required={"name"},
 *     properties={
 *         @OA\Property(property="name", type="string")
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="ArtistResponse",
 *     type="object",
 *     title="ArtistResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="created_at", type="string"),
 *         @OA\Property(property="albums", type="array", @OA\Items(ref="#/components/schemas/AlbumResponse"))
 *     }
 * )
 */

class Artist extends BaseModel
{
    protected $table = 'artist';
    protected $fillable = ['name'];

    public function albums()
    {
        return $this->hasMany('App\Album');
    }
}
