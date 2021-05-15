<?php

use App\Invoice;
use App\Invoiceline;
use App\Track;

class InvoicelineTest extends TestCase
{
    public function testRequiresInvoiceline()
    {
        $this->json('POST', 'api/invoicelines')
            ->seeStatusCode(422)
            ->seeJson([
                'unit_price' => ['The unit price field is required.'],
                'quantity' => ['The quantity field is required.']
            ]);
    }

    public function testStoreNewInvoiceline()
    {
        $invoice = factory(Invoice::class)->create();
        $track = factory(Track::class)->create();

        $payload = ['invoice_id' => $invoice->id,
            'track_id' => $track->id,
            'unit_price' => 0.99,
            'quantity' => 5];


        $this->json('POST', 'api/invoicelines', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'quantity'
            ]);
    }

    public function testRetrieveInvoiceline()
    {
        factory(Invoiceline::class)->create([
            'quantity' => 4
        ]);

        $this->json('GET', '/api/invoicelines')
            ->seeStatusCode(200)
            ->seeJsonContains(
                ['quantity' => "4"]
            )
            ->seeJsonStructure([
                'data' => ['*' => ['quantity']],
            ]);
    }

    public function testDeleteEmployee()
    {
        $invoiceline = factory(Invoiceline::class)->create();

        $this->json('DELETE', '/api/invoicelines/' . $invoiceline->id, [], [])
            ->seeStatusCode(204);
    }
}
