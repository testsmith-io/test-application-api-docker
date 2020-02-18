<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const CREATED_AT = null;
    protected $hidden = ['updated_at'];
}
