<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
 *         @OA\Property(property="created_at", type="string"),
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
class Employee extends BaseModel
{
    protected $table = 'employee';
    protected $fillable = ['firstname', 'lastname', 'title', 'reports_to', 'birthdate', 'hiredate', 'address', 'city', 'state', 'country', 'postalcode', 'phone', 'fax', 'email'];

    public function customers()
    {
        return $this->hasMany('App\Customer', 'support_rep_id');
    }

    public function reportsTo()
    {
        return $this->belongsTo('App\Employee', 'reports_to');
    }

}
