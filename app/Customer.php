<?php

namespace App;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

/**
 * Class Customer
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="CustomerRequest",
 *     type="object",
 *     title="CustomerRequest",
 *     required={"firstname", "lastname", "address", "city", "country", "email"},
 *     properties={
 *         @OA\Property(property="firstname", type="string"),
 *         @OA\Property(property="lastname", type="string"),
 *         @OA\Property(property="company", type="string"),
 *         @OA\Property(property="address", type="string"),
 *         @OA\Property(property="city", type="string"),
 *         @OA\Property(property="state", type="string"),
 *         @OA\Property(property="country", type="string"),
 *         @OA\Property(property="postalcode", type="string"),
 *         @OA\Property(property="phone", type="string"),
 *         @OA\Property(property="fax", type="string"),
 *         @OA\Property(property="email", type="string"),
 *         @OA\Property(property="password", type="string"),
 *         @OA\Property(property="support_rep_id", type="integer")
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="CustomerResponse",
 *     type="object",
 *     title="CustomerResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="firstname", type="string"),
 *         @OA\Property(property="lastname", type="string"),
 *         @OA\Property(property="company", type="string"),
 *         @OA\Property(property="address", type="string"),
 *         @OA\Property(property="city", type="string"),
 *         @OA\Property(property="state", type="string"),
 *         @OA\Property(property="country", type="string"),
 *         @OA\Property(property="postalcode", type="string"),
 *         @OA\Property(property="phone", type="string"),
 *         @OA\Property(property="fax", type="string"),
 *         @OA\Property(property="email", type="string"),
 *         @OA\Property(property="support_rep_id", type="integer"),
 *         @OA\Property(property="created_at", type="string"),
 *         @OA\Property(property="support_rep", ref="#/components/schemas/EmployeeResponse")
 *     }
 * )
 */
class Customer extends Model implements AuthenticatableContract, AuthorizableContract, AuthenticatableUserContract
{
    use \Illuminate\Auth\Authenticatable, Authorizable;

    const CREATED_AT = null;
    protected $hidden = ['updated_at', 'password'];
    protected $table = 'customer';
    protected $fillable = ['firstname', 'lastname', 'company', 'address', 'city', 'state', 'country', 'postalcode', 'phone', 'fax', 'email', 'password', 'support_rep_id'];

    public function supportRep()
    {
        return $this->belongsTo('App\Employee', 'support_rep_id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
