<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTrack;
use App\Http\Requests\StoreTrack;
use App\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrackController extends Controller
{

    /**
     * @OA\Get(
     *     path="/tracks",
     *     summary="Retrieve all tracks",
     *     operationId="get-all-tracks",
     *     tags={"Track"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page, default=1",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All tracks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TrackResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return $this->preferredFormat(Track::paginate());
    }

    /**
     * @OA\Post(
     *     path="/tracks",
     *     summary="New track",
     *     operationId="store-track",
     *     tags={"Track"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Track object",
     *         @OA\JsonContent(ref="#/components/schemas/TrackRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A track",
     *         @OA\JsonContent(ref="#/components/schemas/TrackResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreTrack $request)
    {
        return $this->preferredFormat(Track::create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/tracks/{trackId}",
     *     summary="Retrieve a specific track",
     *     operationId="get-track",
     *     tags={"Track"},
     *     @OA\Parameter(
     *         name="trackId",
     *         in="path",
     *         description="The trackId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A track",
     *         @OA\JsonContent(ref="#/components/schemas/TrackResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return $this->preferredFormat(Track::findOrFail($id));
    }

    /**
     * @OA\Get(
     *     path="/tracks/search",
     *     summary="Retrieve specific tracks matching the search query",
     *     operationId="search-track",
     *     tags={"Track"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="The query parameter in path",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All matching tracks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TrackResponse")
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

        return $this->preferredFormat(Track::where('name','like',"%{$q}%")->orWhere('composer','like',"%{$q}%")->get());
    }

    /**
     * @OA\Put(
     *     path="/tracks/{trackId}",
     *     summary="Update track",
     *     operationId="update-track",
     *     tags={"Track"},
     *     @OA\Parameter(
     *         name="trackId",
     *         in="path",
     *         description="The trackId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Track object",
     *         @OA\JsonContent(ref="#/components/schemas/TrackRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A track",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TrackResponse")
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
    public function update(StoreTrack $request, $id)
    {
        return $this->preferredFormat(['success' => (bool) Track::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/tracks/{trackId}",
     *     summary="Remove track",
     *     operationId="destroy-track",
     *     tags={"Track"},
     *     @OA\Parameter(
     *         name="trackId",
     *         in="path",
     *         description="The trackId parameter in path",
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
    public function destroy(DestroyTrack $request, $id)
    {
        try {
            Track::find($id)->delete();
            return $this->preferredFormat(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->preferredFormat([
                    'success' => false,
                    'message' => 'Seems like this playlist is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
