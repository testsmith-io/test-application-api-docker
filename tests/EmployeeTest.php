<?php


use App\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class EmployeeTest extends TestCase
{
    public function testRequiresEmployee()
    {
        $this->json('POST', 'api/employees')
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

    public function testStoreNewEmployee()
    {
        $payload = ['firstname' => 'new',
            'lastname' => 'new',
            'title' => 'new',
            'hiredate' => '2001-01-01',
            'birthdate' => '2001-01-01',
            'address' => 'new',
            'city' => 'new',
            'country' => 'new',
            'state' => 'new',
            'postalcode' => '1234ab',
            'email' => 'new',
            'phone' => '0987654321',
            'fax' => '0987654321'];

        $this->json('POST', 'api/employees', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'employee' => [
                    'firstname'
                ],
            ]);
    }

    public function testRetrieveEmployee()
    {
        factory(Employee::class)->create([
            'firstname' => 'Test'
        ]);

        $this->json('GET', '/api/employees')
            ->seeStatusCode(200)
            ->seeJsonContains(
                ['firstname' => 'Test']
            )
            ->seeJsonStructure([
                '*' => ['firstname'],
            ]);
    }

    public function testDeleteEmployee()
    {
        $employee = factory(Employee::class)->create();

        $this->json('DELETE', '/api/employees/' . $employee->id, [], [])
            ->seeStatusCode(204);
    }
}
