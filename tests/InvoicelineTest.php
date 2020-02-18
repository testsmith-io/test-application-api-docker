<?php

use App\Invoiceline;

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
        $payload = ['invoice_id' => 3,
            'track_id' => 3,
            'unit_price' => 0.99,
            'quantity' => 5];

        $this->json('POST', 'api/invoicelines', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'invoiceline' => [
                    'quantity'
                ],
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
                '*' => ['quantity'],
            ]);
    }

    public function testDeleteEmployee()
    {
        $invoiceline = factory(Invoiceline::class)->create();

        $this->json('DELETE', '/api/invoicelines/' . $invoiceline->id, [], [])
            ->seeStatusCode(204);
    }
}
