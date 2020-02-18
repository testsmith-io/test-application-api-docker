<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Mediatype
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="MediatypeRequest",
 *     type="object",
 *     title="MediatypeRequest",
 *     required={"title", "body"},
 *     properties={
 *         @OA\Property(property="name", type="string")
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="MediatypeResponse",
 *     type="object",
 *     title="MediatypeResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="created_at", type="string")
 *     }
 * )
 */

class Mediatype extends Model
{
    protected $table = 'mediatype';
    protected $fillable = ['name'];
}
