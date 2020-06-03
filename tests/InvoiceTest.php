<?php

use App\Customer;
use App\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;

class InvoiceTest extends TestCase
{
    public function testRequiresInvoice()
    {
        $this->refreshApplication();
        $this->post('api/invoices', [], $this->headers(factory(Customer::class)->create([
            'firstname' => 'Test'
        ])))
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
        $customer = factory(Customer::class)->create();

        $payload = ['billing_address' => 'new',
            'customer_id' => $customer->id,
            'invoice_date' => '1986-01-01',
            'billing_city' => 'new',
            'billing_country' => 'new',
            'total' => 10];

        $this->refreshApplication();
        $this->json('POST', 'api/invoices', $payload, $this->headers(factory(Customer::class)->create([
            'firstname' => 'Test'
        ])))
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'invoice' => [
                    'billing_address'
                ],
            ]);
    }

    public function testRetrieveInvoice()
    {
        $customer = factory(Customer::class)->create([
            'firstname' => 'Test'
        ]);
        factory(Invoice::class)->create([
            'billing_address' => 'Test',
            'customer_id' => $customer->id
        ]);

        $this->refreshApplication();
        $response = $this->get('/api/invoices', $this->headers($customer));
        $response->seeStatusCode(200)
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

        $this->refreshApplication();
        $this->json('DELETE', '/api/invoices/' . $invoice->id, [], $this->headers(factory(Customer::class)->create([
            'firstname' => 'Test'
        ])))
            ->seeStatusCode(204);
    }
}
