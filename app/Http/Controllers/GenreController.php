<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Http\Requests\DestroyGenre;
use App\Http\Requests\StoreGenre;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/genres",
     *     summary="Retrieve all genres",
     *     operationId="get-all-genres",
     *     tags={"Genre"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page, default=1",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All genre",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/GenreResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return response()->preferredFormat(Genre::paginate());
    }

    /**
     * @OA\Post(
     *     path="/genres",
     *     summary="New genre",
     *     operationId="store-genre",
     *     tags={"Genre"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Genre object",
     *         @OA\JsonContent(ref="#/components/schemas/GenreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Genre",
     *         @OA\JsonContent(ref="#/components/schemas/GenreResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreGenre $request)
    {
        return response()->preferredFormat(['genre' => Genre::create($request->all())], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/genres/{genreId}",
     *     summary="Retrieve a specific genre",
     *     operationId="get-genre",
     *     tags={"Genre"},
     *     @OA\Parameter(
     *         name="genreId",
     *         in="path",
     *         description="The genreId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Genre",
     *         @OA\JsonContent(ref="#/components/schemas/GenreResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return response()->preferredFormat(Genre::findOrFail($id));
    }

    /**
     * @OA\Get(
     *     path="/genres/search",
     *     summary="Retrieve specific genres matching the search query",
     *     operationId="search-genre",
     *     tags={"Genre"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="The query parameter in path",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All matching genres",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/GenreResponse")
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

        return response()->preferredFormat(Genre::where('name','like',"%{$q}%")->get());
    }

    /**
     * @OA\Put(
     *     path="/genres/{genreId}",
     *     summary="Update genre",
     *     operationId="update-genre",
     *     tags={"Genre"},
     *     @OA\Parameter(
     *         name="genreId",
     *         in="path",
     *         description="The genreId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Genre object",
     *         @OA\JsonContent(ref="#/components/schemas/GenreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Genre",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/GenreResponse")
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
    public function update(StoreGenre $request, $id)
    {
        return response()->preferredFormat(['success' => (bool)Genre::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/genres/{genreId}",
     *     summary="Remove genre",
     *     operationId="destroy-genre",
     *     tags={"Genre"},
     *     @OA\Parameter(
     *         name="genreId",
     *         in="path",
     *         description="The genreId parameter in path",
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
    public function destroy(DestroyGenre $request, $id)
    {
        try {
            Genre::find($id)->delete();
            return response()->preferredFormat(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->preferredFormat([
                    'success' => false,
                    'message' => 'Seems like this genre is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
