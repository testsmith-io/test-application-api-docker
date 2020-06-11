<?php

use App\Customer;
use App\Employee;
use App\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class InvoiceTest extends TestCase
{
    public function testRequiresInvoice()
    {
        $this->actingAs(factory(Employee::class)->create(), 'customer')->post('api/invoices', [], [])
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

        $this->actingAs($customer, 'customer')->json('POST', 'api/invoices', $payload, [])
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
