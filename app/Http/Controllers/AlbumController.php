<?php

namespace App\Http\Controllers;

use App\Album;
use App\Http\Requests\DestroyAlbum;
use App\Http\Requests\StoreAlbum;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AlbumController extends Controller
{

    /**
     * @OA\Get(
     *     path="/albums",
     *     summary="Retrieve all albums",
     *     operationId="get-all-albums",
     *     tags={"Album"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page, default=1",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All albums",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AlbumResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return $this->preferredFormat(Album::with('artist')->paginate());
    }

    /**
     * @OA\Post(
     *     path="/albums",
     *     summary="New album",
     *     operationId="store-album",
     *     tags={"Album"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Album object",
     *         @OA\JsonContent(ref="#/components/schemas/AlbumRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Album",
     *         @OA\JsonContent(ref="#/components/schemas/AlbumResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreAlbum $request)
    {
        return $this->preferredFormat(['album' => Album::create($request->all())], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/albums/{albumId}",
     *     summary="Retrieve a specific album",
     *     operationId="get-album",
     *     tags={"Album"},
     *     @OA\Parameter(
     *         name="albumId",
     *         in="path",
     *         description="The albumId parameter in path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Album",
     *         @OA\JsonContent(ref="#/components/schemas/AlbumResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return $this->preferredFormat(Album::with('artist')->findOrFail($id));
    }

    /**
     * @OA\Get(
     *     path="/albums/search",
     *     summary="Retrieve specific albums matching the search query",
     *     operationId="search-album",
     *     tags={"Album"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="The query parameter in path",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All matching albums",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AlbumResponse")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function search(Request $request)
    {
        $q = $request->get('q');

        return $this->preferredFormat(Album::with('artist')->where('title','like',"%{$q}%")->get());
    }

    /**
     * @OA\Put(
     *     path="/albums/{albumId}",
     *     summary="Update album",
     *     operationId="update-album",
     *     tags={"Album"},
     *     @OA\Parameter(
     *         name="albumId",
     *         in="path",
     *         description="The albumId parameter in path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Album object",
     *         @OA\JsonContent(ref="#/components/schemas/AlbumRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Album",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AlbumResponse")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function update(StoreAlbum $request, $id)
    {
        return $this->preferredFormat(['success' => (bool)Album::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/albums/{albumId}",
     *     summary="Remove album",
     *     operationId="destroy-album",
     *     tags={"Album"},
     *     @OA\Parameter(
     *         name="albumId",
     *         in="path",
     *         description="The albumId parameter in path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function destroy(DestroyAlbum $request, $id)
    {
        try {
            Album::find($id)->delete();
            return $this->preferredFormat(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->preferredFormat([
                    'success' => false,
                    'message' => 'Seems like this album is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
