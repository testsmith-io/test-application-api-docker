<?php

use App\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class InvoiceTest extends TestCase
{
    public function testRequiresInvoice()
    {
        $this->json('POST', 'api/invoices')
            ->seeStatusCode(422)
            ->seeJson([
                'billing_address' => ['The billing address field is required.'],
                'billing_city' => ['The billing city field is required.'],
                'billing_country' => ['The billing country field is required.'],
                'total' => ['The total field is required.']
            ]);
    }


    public function testStoreNewInvoice()
    {
        $payload = ['billing_address' => 'new',
            'customer_id' => 3,
            'invoice_date' => '1-1-1986',
            'billing_city' => 'new',
            'billing_country' => 'new',
            'total' => 10];

        $this->json('POST', 'api/invoices', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'invoice' => [
                    'billing_address'
                ],
            ]);
    }

    public function testRetrieveInvoice()
    {
        factory(Invoice::class)->create([
            'billing_address' => 'Test'
        ]);

        $this->json('GET', '/api/invoices')
            ->seeStatusCode(200)
            ->seeJsonContains(
                ['billing_address' => 'Test']
            )
            ->seeJsonStructure([
                '*' => ['billing_address'],
            ]);
    }

    public function testDeleteInvoice()
    {
        $invoice = factory(Invoice::class)->create();

        $this->json('DELETE', '/api/invoices/' . $invoice->id, [], [])
            ->seeStatusCode(204);
    }
}
