<?php

use App\Album;

class AlbumTest extends TestCase
{
    public function testRequiresAlbum()
    {
        $this->json('POST', 'api/albums')
            ->seeStatusCode(422)
            ->seeJson([
                'title' => ['The title field is required.']
            ]);
    }

    public function testStoreNewAlbum()
    {
        $payload = ['title' => 'new', 'artist_id' => 3];

        $this->json('POST', 'api/albums', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'title'
            ]);
    }

    public function testRetrieveAlbum()
    {
        factory(Album::class)->create([
            'title' => 'Test'
        ]);

        $this->json('GET', '/api/albums')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'data' => ['*' => ['title']],
            ]);
    }

    public function testDeleteAlbum()
    {
        $album = factory(Album::class)->create([
            'title' => 'Test'
        ]);

        $this->delete('/api/albums/' . $album->id)
            ->seeStatusCode(204);
    }

    public function testShowAlbum()
    {
        $album = factory(Album::class)->create([
            'title' => 'Test'
        ]);

        $this->json('GET', '/api/albums/' . $album->id, [], [])
            ->seeStatusCode(200);
    }

    public function testUpdateAlbum()
    {
        $album = factory(Album::class)->create([
            'title' => 'Test2'
        ]);

        $payload = ['title' => 'new', 'artist_id' => 3];

        $this->json('PUT', 'api/albums/' . $album->id, $payload)
            ->seeStatusCode(200)
            ->seeJson([
                'success' => true
            ]);
    }

}
