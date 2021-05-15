<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Invoice
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="InvoiceRequest",
 *     type="object",
 *     title="InvoiceRequest",
 *     required={"title", "body"},
 *     properties={
 *         @OA\Property(property="customer_id", type="integer"),
 *         @OA\Property(property="invoice_date", type="string"),
 *         @OA\Property(property="billing_address", type="string"),
 *         @OA\Property(property="billing_city", type="string"),
 *         @OA\Property(property="billing_state", type="string"),
 *         @OA\Property(property="billing_country", type="string"),
 *         @OA\Property(property="billing_postalcode", type="string"),
 *         @OA\Property(property="total", type="string"),
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="InvoiceResponse",
 *     type="object",
 *     title="InvoiceResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="customer_id", type="integer"),
 *         @OA\Property(property="invoice_date", type="string"),
 *         @OA\Property(property="billing_address", type="string"),
 *         @OA\Property(property="billing_city", type="string"),
 *         @OA\Property(property="billing_state", type="string"),
 *         @OA\Property(property="billing_country", type="string"),
 *         @OA\Property(property="billing_postalcode", type="string"),
 *         @OA\Property(property="total", type="string"),
 *         @OA\Property(property="created_at", type="string"),
 *         @OA\Property(property="customer", ref="#/components/schemas/CustomerResponse")
 *     }
 * )
 */
class Invoice extends BaseModel
{
    protected $table = 'invoice';
    protected $fillable = ['customer_id', 'invoice_date', 'billing_address', 'billing_city', 'billing_state', 'billing_country', 'billing_postalcode', 'total'];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
}
