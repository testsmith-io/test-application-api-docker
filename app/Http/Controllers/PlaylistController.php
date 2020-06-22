<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyPlaylist;
use App\Http\Requests\StorePlaylist;
use App\Playlist;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class PlaylistController extends Controller
{

    /**
     * @OA\Get(
     *     path="/playlists",
     *     summary="Retrieve all playlists",
     *     operationId="get-all-playlists",
     *     tags={"Playlist"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page, default=1",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All playlists",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PlaylistResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return $this->jsonResponse(Playlist::paginate());
    }

    /**
     * @OA\Post(
     *     path="/playlists",
     *     summary="New playlist",
     *     operationId="store-playlist",
     *     tags={"Playlist"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Playlist object",
     *         @OA\JsonContent(ref="#/components/schemas/PlaylistRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Playlist",
     *         @OA\JsonContent(ref="#/components/schemas/PlaylistResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StorePlaylist $request)
    {
        return $this->jsonResponse(['playlist' => Playlist::create($request->all())], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/playlists/{playlistId}",
     *     summary="Retrieve a specific playlist",
     *     operationId="get-playlist",
     *     tags={"Playlist"},
     *     @OA\Parameter(
     *         name="playlistId",
     *         in="path",
     *         description="The playlistId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Playlist",
     *         @OA\JsonContent(ref="#/components/schemas/PlaylistResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return $this->jsonResponse(Playlist::find($id));
    }

    /**
     * @OA\Put(
     *     path="/playlists/{playlistId}",
     *     summary="Update playlist",
     *     operationId="update-playlist",
     *     tags={"Playlist"},
     *     @OA\Parameter(
     *         name="playlistId",
     *         in="path",
     *         description="The playlistId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Playlist object",
     *         @OA\JsonContent(ref="#/components/schemas/PlaylistRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Playlist",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PlaylistResponse")
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
    public function update(StorePlaylist $request, $id)
    {
        return $this->jsonResponse(['success' => (bool)Playlist::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/playlists/{playlistId}",
     *     summary="Remove playlist",
     *     operationId="destroy-playlist",
     *     tags={"Playlist"},
     *     @OA\Parameter(
     *         name="playlistId",
     *         in="path",
     *         description="The playlistId parameter in path",
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
    public function destroy(DestroyPlaylist $request, $id)
    {
        try {
            Playlist::find($id)->delete();
            return $this->jsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Seems like this playlist is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}