<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Invoiceline
 * @property string $title
 * @property string $body
 * @package App\ViewModels
 * @OA\Schema(
 *     schema="InvoicelineRequest",
 *     type="object",
 *     title="InvoicelineRequest",
 *     required={"title", "body"},
 *     properties={
 *         @OA\Property(property="invoice_id", type="integer"),
 *         @OA\Property(property="track_id", type="integer"),
 *         @OA\Property(property="unit_price", type="string"),
 *         @OA\Property(property="quantity", type="integer"),
 *     }
 * )
 **/
/**
 * @OA\Schema(
 *     schema="InvoicelineResponse",
 *     type="object",
 *     title="InvoicelineResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="invoice_id", type="integer"),
 *         @OA\Property(property="track_id", type="integer"),
 *         @OA\Property(property="unit_price", type="string"),
 *         @OA\Property(property="quantity", type="integer"),
 *         @OA\Property(property="created_at", type="string")
 *     }
 * )
 */

class Invoiceline extends BaseModel
{
    protected $table = 'invoiceline';
    protected $fillable = ['invoice_id', 'track_id', 'unit_price', 'quantity'];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    public function track()
    {
        return $this->belongsTo('App\Track');
    }
}
