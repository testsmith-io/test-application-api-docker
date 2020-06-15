<?php

use App\Artist;

class ArtistTest extends TestCase
{
    public function testRequiresArtist()
    {
        $this->json('POST', 'api/artists')
            ->seeStatusCode(422)
            ->seeJson([
                'name' => ['The name field is required.']
            ]);
    }

    public function testStoreNewArtist()
    {
        $payload = ['name' => 'new'];

        $this->json('POST', 'api/artists', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'artist' => [
                    'name'
                ],
            ]);
    }

    public function testRetrieveArtist()
    {
        factory(Artist::class)->create([
            'name' => 'Test'
        ]);

        $this->json('GET', '/api/artists')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'data' => ['*' => ['name', 'albums']],
            ]);
    }

    public function testDeleteArtist()
    {
        $artist = factory(Artist::class)->create([
            'name' => 'Test'
        ]);

        $this->delete('/api/artists/' . $artist->id)
            ->seeStatusCode(204);
    }

    public function testDeleteNonExistingArtist()
    {
        $this->delete('/api/artists/99')
            ->seeStatusCode(422)
            ->seeJson([
                'id' => ['The selected id is invalid.']
            ]);
    }

    public function testShowArtist()
    {
        $artist = factory(Artist::class)->create([
            'name' => 'Test'
        ]);

        $this->json('GET', '/api/artists/' . $artist->id, [], [])
            ->seeStatusCode(200);
    }

    public function testUpdateArtist()
    {
        $artist = factory(Artist::class)->create([
            'name' => 'Test2'
        ]);

        $payload = ['name' => 'new'];

        $this->json('PUT', 'api/artists/' . $artist->id, $payload)
            ->seeStatusCode(200)
            ->seeJson([
                'success' => true
            ]);
    }

}
