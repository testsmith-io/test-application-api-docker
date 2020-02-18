<?php

use App\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class GenreTest extends TestCase
{
    public function testRequiresGenre()
    {
        $this->json('POST', 'api/genres')
            ->seeStatusCode(422)
            ->seeJson([
                'name' => ['The name field is required.']
            ]);
    }

    public function testStoreNewGenre()
    {
        $payload = ['name' => 'new'];

        $this->json('POST', 'api/genres', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'genre' => [
                    'name'
                ],
            ]);
    }

    public function testRetrieveGenre()
    {
        factory(Genre::class)->create([
            'name' => 'Test'
        ]);

        $this->json('GET', '/api/genres')
            ->seeStatusCode(200)
            ->seeJsonContains(
                ['name' => 'Test']
            )
            ->seeJsonStructure([
                '*' => ['name'],
            ]);
    }

    public function testDeleteGenre()
    {
        $genre = factory(Genre::class)->create([
            'name' => 'Test'
        ]);

        $this->json('DELETE', '/api/genres/' . $genre->id, [], [])
            ->seeStatusCode(204);
    }
}
