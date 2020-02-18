<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
 *         @OA\Property(property="lastname", type="integer"),
 *         @OA\Property(property="company", type="string"),
 *         @OA\Property(property="address", type="string"),
 *         @OA\Property(property="city", type="integer"),
 *         @OA\Property(property="state", type="string"),
 *         @OA\Property(property="country", type="string"),
 *         @OA\Property(property="postalcode", type="integer"),
 *         @OA\Property(property="phone", type="string"),
 *         @OA\Property(property="fax", type="string"),
 *         @OA\Property(property="email", type="integer"),
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
 *         @OA\Property(property="lastname", type="integer"),
 *         @OA\Property(property="company", type="string"),
 *         @OA\Property(property="address", type="string"),
 *         @OA\Property(property="city", type="integer"),
 *         @OA\Property(property="state", type="string"),
 *         @OA\Property(property="country", type="string"),
 *         @OA\Property(property="postalcode", type="integer"),
 *         @OA\Property(property="phone", type="string"),
 *         @OA\Property(property="fax", type="string"),
 *         @OA\Property(property="email", type="integer"),
 *         @OA\Property(property="support_rep_id", type="integer"),
 *         @OA\Property(property="created_at", type="string"),
 *         @OA\Property(property="support_rep", ref="#/components/schemas/EmployeeResponse")
 *     }
 * )
 */
class Customer extends BaseModel
{
    protected $table = 'customer';
    protected $fillable = ['firstname', 'lastname', 'company', 'address', 'city', 'state', 'country', 'postalcode', 'phone', 'fax', 'email', 'support_rep_id'];

    public function supportRep()
    {
        return $this->belongsTo('App\Employee', 'support_rep_id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}
