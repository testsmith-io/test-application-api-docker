<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Http\Requests\DestroyArtist;
use App\Http\Requests\StoreArtist;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArtistController extends Controller
{
    /**
     * @OA\Get(
     *     path="/artists",
     *     summary="Retrieve all artists",
     *     operationId="get-all-artists",
     *     tags={"Artist"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page, default=1",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All artists",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ArtistResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return $this->jsonResponse(Artist::with('albums')->paginate());
    }

    /**
     * @OA\Post(
     *     path="/artists",
     *     summary="New artist",
     *     operationId="store-artist",
     *     tags={"Artist"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Album object",
     *         @OA\JsonContent(ref="#/components/schemas/ArtistRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Album",
     *         @OA\JsonContent(ref="#/components/schemas/ArtistResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreArtist $request)
    {
        return $this->jsonResponse(['artist' => Artist::create($request->all())], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/artists/{artistId}",
     *     summary="Retrieve a specific artist",
     *     operationId="get-artist",
     *     tags={"Artist"},
     *     @OA\Parameter(
     *         name="artistId",
     *         in="path",
     *         description="The artistId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Artist",
     *         @OA\JsonContent(ref="#/components/schemas/ArtistResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return $this->jsonResponse(Artist::with('albums')->findOrFail($id));
    }

    /**
     * @OA\Get(
     *     path="/artists/search",
     *     summary="Retrieve specific artists matching the search query",
     *     operationId="search-artist",
     *     tags={"Artist"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="The query parameter in path",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All matching artists",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ArtistResponse")
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

        return $this->jsonResponse(Artist::with('albums')->where('name','like',"%{$q}%")->get());
    }

    /**
     * @OA\Put(
     *     path="/artists/{artistId}",
     *     summary="Update artist",
     *     operationId="update-artist",
     *     tags={"Artist"},
     *     @OA\Parameter(
     *         name="artistId",
     *         in="path",
     *         description="The artistId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Artist object",
     *         @OA\JsonContent(ref="#/components/schemas/ArtistRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Artist",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ArtistResponse")
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
    public function update(StoreArtist $request, $id)
    {
        return $this->jsonResponse(['success' => (bool) Artist::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/artists/{artistId}",
     *     summary="Remove artist",
     *     operationId="destroy-artist",
     *     tags={"Artist"},
     *     @OA\Parameter(
     *         name="artistId",
     *         in="path",
     *         description="The artistId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
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
    public function destroy(DestroyArtist $request, $id)
    {
        try {
            Artist::find($id)->delete();
            return $this->jsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Seems like this artist is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
