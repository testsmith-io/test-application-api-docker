<?php

use App\Customer;
use App\Employee;

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
        $employee = factory(Employee::class)->create();

        $payload = ['firstname' => 'new',
            'lastname' => 'new',
            'address' => 'new',
            'city' => 'new',
            'country' => 'new',
            'email' => 'new',
            'password' => 'Test1234',
            'support_rep_id' => $employee->id];

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
        $this->refreshApplication();
        $this->get( '/api/customers', $this->headers(factory(Customer::class)->create([
            'firstname' => 'Test'
        ])))
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
        $this->refreshApplication();
        $customer = factory(Customer::class)->create();
        $this->json( "DELETE", '/api/customers/' . $customer->id, [], $this->headers($customer))
            ->seeStatusCode(204);
    }

    public function testShowCustomer()
    {
        $customer = factory(Customer::class)->create([
            'firstname' => 'Test'
        ]);
        $this->refreshApplication();
        $this->get( '/api/customers/' . $customer->id,$this->headers(factory(Customer::class)->create([
            'firstname' => 'Test'
        ])))
            ->seeStatusCode(200);
    }

    public function testUpdateCustomer()
    {
        $customer = factory(Customer::class)->create([
            'firstname' => 'Test2'
        ]);

        $payload = ['firstname' => 'new'];
        $this->refreshApplication();
        $this->put( 'api/customers/' . $customer->id, $payload, $this->headers(factory(Customer::class)->create([
            'firstname' => 'Test'
        ])))
            ->seeStatusCode(200)
            ->seeJson([
                'success' => true
            ]);
    }

}
