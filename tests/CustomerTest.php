<?php

use App\Customer;

class CustomerTest extends TestCase
{
    public function testRequiresCustomer()
    {
        $this->json('POST', 'api/customers')
            ->seeStatusCode(422)
            ->seeJson([
                'firstname' => ['The firstname field is required.'],
                'lastname' => ['The lastname field is required.'],
                'address' => ['The address field is required.'],
                'city' => ['The city field is required.'],
                'country' => ['The country field is required.'],
                'email' => ['The email field is required.']
            ]);
    }

    public function testStoreNewCustomer()
    {
        $payload = ['firstname' => 'new',
            'lastname' => 'new',
            'address' => 'new',
            'city' => 'new',
            'country' => 'new',
            'email' => 'new',
            'support_rep_id' => 3];

        $this->json('POST', 'api/customers', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'customer' => [
                    'firstname'
                ],
            ]);
    }

    public function testRetrieveCustomer()
    {
        factory(Customer::class)->create([
            'firstname' => 'Test'
        ]);

        $this->json('GET', '/api/customers')
            ->seeStatusCode(200)
            ->seeJsonContains(
                ['firstname' => 'Test']
            )
            ->seeJsonStructure([
                '*' => ['firstname'],
            ]);
    }

    public function testDeleteCustomer()
    {
        $customer = factory(Customer::class)->create();

        $this->json('DELETE', '/api/customers/' . $customer->id, [], [])
            ->seeStatusCode(204);
    }

    public function testShowCustomer()
    {
        $customer = factory(Customer::class)->create([
            'firstname' => 'Test'
        ]);

        $this->json('GET', '/api/customers/' . $customer->id, [], [])
            ->seeStatusCode(200);
    }

    public function testUpdateCustomer()
    {
        $customer = factory(Customer::class)->create([
            'firstname' => 'Test2'
        ]);

        $payload = ['firstname' => 'new'];

        $this->json('PUT', 'api/customers/' . $customer->id, $payload)
            ->seeStatusCode(200)
            ->seeJson([
                'success' => true
            ]);
    }

}
