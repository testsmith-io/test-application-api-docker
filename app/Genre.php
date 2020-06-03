<?php

namespace App;

/**
 * Class Genre
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="GenreRequest",
 *     type="object",
 *     title="GenreRequest",
 *     required={"name"},
 *     properties={
 *         @OA\Property(property="name", type="string")
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="GenreResponse",
 *     type="object",
 *     title="GenreResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="created_at", type="string")
 *     }
 * )
 */

class Genre extends BaseModel
{
    protected $table = 'genre';
    protected $fillable = ['name'];

}
