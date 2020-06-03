<?php

namespace App;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

/**
 * Class Employee
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="EmployeeRequest",
 *     type="object",
 *     title="EmployeeRequest",
 *     required={"title", "body"},
 *     properties={
 *         @OA\Property(property="lastname", type="string"),
 *         @OA\Property(property="firstname", type="string"),
 *         @OA\Property(property="title", type="string"),
 *         @OA\Property(property="reports_to", ref="integer"),
 *         @OA\Property(property="birthdate", type="string"),
 *         @OA\Property(property="hiredate", type="string"),
 *         @OA\Property(property="address", type="string"),
 *         @OA\Property(property="city", type="string"),
 *         @OA\Property(property="state", type="string"),
 *         @OA\Property(property="country", type="string"),
 *         @OA\Property(property="postalcode", type="string"),
 *         @OA\Property(property="phone", type="string"),
 *         @OA\Property(property="fax", type="string"),
 *         @OA\Property(property="email", type="string"),
 *         @OA\Property(property="password", type="string"),
 *     }
 * )
 **/

/**
 * @OA\Schema(
 *     schema="EmployeeResponse",
 *     type="object",
 *     title="EmployeeResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="lastname", type="string"),
 *         @OA\Property(property="firstname", type="string"),
 *         @OA\Property(property="title", type="string"),
 *         @OA\Property(property="reports_to", ref="#/components/schemas/EmployeeResponse"),
 *         @OA\Property(property="birthdate", type="string"),
 *         @OA\Property(property="hiredate", type="string"),
 *         @OA\Property(property="address", type="string"),
 *         @OA\Property(property="city", type="string"),
 *         @OA\Property(property="state", type="string"),
 *         @OA\Property(property="country", type="string"),
 *         @OA\Property(property="postalcode", type="string"),
 *         @OA\Property(property="phone", type="string"),
 *         @OA\Property(property="fax", type="string"),
 *         @OA\Property(property="email", type="string"),
 *         @OA\Property(property="created_at", type="string"),
 *         @OA\Property(property="customers", type="array", @OA\Items(ref="#/components/schemas/CustomerResponse"))
 *     }
 * )
 */
class Employee extends Model implements AuthenticatableContract, AuthorizableContract, AuthenticatableUserContract
{
    use \Illuminate\Auth\Authenticatable, Authorizable;

    const CREATED_AT = null;

    protected $table = 'employee';
    protected $hidden = ['updated_at', 'password'];
    protected $fillable = ['firstname', 'lastname', 'title', 'reports_to', 'birthdate', 'hiredate', 'address', 'city', 'state', 'country', 'postalcode', 'phone', 'fax', 'email', 'password'];

    public function customers()
    {
        return $this->hasMany('App\Customer', 'support_rep_id');
    }

    public function reportsTo()
    {
        return $this->belongsTo('App\Employee', 'reports_to');
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
