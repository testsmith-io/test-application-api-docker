<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Playlist
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="PlaylistRequest",
 *     type="object",
 *     title="PlaylistRequest",
 *     required={"name"},
 *     properties={
 *         @OA\Property(property="name", type="string")
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="PlaylistResponse",
 *     type="object",
 *     title="PlaylistResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="created_at", type="string")
 *     }
 * )
 */

class Playlist extends BaseModel
{
    protected $table = 'playlist';
    protected $fillable = ['name'];
}
