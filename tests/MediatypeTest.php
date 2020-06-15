<?php

use App\Mediatype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class MediatypeTest extends TestCase
{
    public function testRequiresMediatype()
    {
        $this->json('POST', 'api/mediatypes')
            ->seeStatusCode(422)
            ->seeJson([
                'name' => ['The name field is required.']
            ]);
    }

    public function testStoreNewMediatype()
    {
        $payload = ['name' => 'new'];

        $this->json('POST', 'api/mediatypes', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'mediatype' => [
                    'name'
                ],
            ]);
    }

    public function testRetrieveMediatype()
    {
        factory(Mediatype::class)->create([
            'name' => 'Test'
        ]);

        $this->json('GET', '/api/mediatypes')
            ->seeStatusCode(200)
            ->seeJsonContains(
                ['name' => 'Test']
            )
            ->seeJsonStructure([
                'data' => ['*' => ['name']],
            ]);
    }

    public function testDeleteMediatype()
    {
        $mediatype = factory(Mediatype::class)->create([
            'name' => 'Test'
        ]);

        $this->json('DELETE', '/api/mediatypes/' . $mediatype->id, [], [])
            ->seeStatusCode(204);
    }
}
