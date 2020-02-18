<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyMediatype;
use App\Http\Requests\StoreMediatype;
use App\Mediatype;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class MediatypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/mediatypes",
     *     summary="Retrieve all mediatypes",
     *     operationId="get-all-mediatypes",
     *     tags={"Mediatype"},
     *     @OA\Response(
     *         response=200,
     *         description="All mediatypes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MediatypeResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return $this->jsonResponse(Mediatype::all());
    }

    /**
     * @OA\Post(
     *     path="/mediatypes",
     *     summary="New mediatype",
     *     operationId="store-mediatype",
     *     tags={"Mediatype"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Mediatype object",
     *         @OA\JsonContent(ref="#/components/schemas/MediatypeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Album",
     *         @OA\JsonContent(ref="#/components/schemas/MediatypeResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreMediatype $request)
    {
        return $this->jsonResponse(['mediatype' => Mediatype::create($request->all())], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/mediatypes/{mediatypeId}",
     *     summary="Retrieve a specific mediatype",
     *     operationId="get-mediatype",
     *     tags={"Mediatype"},
     *     @OA\Parameter(
     *         name="mediatypeId",
     *         in="path",
     *         description="The mediatypeId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Mediatype",
     *         @OA\JsonContent(ref="#/components/schemas/MediatypeResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return $this->jsonResponse(Mediatype::find($id));
    }

    /**
     * @OA\Put(
     *     path="/mediatypes/{mediatypeId}",
     *     summary="Update mediatype",
     *     operationId="update-mediatype",
     *     tags={"Mediatype"},
     *     @OA\Parameter(
     *         name="mediatypeId",
     *         in="path",
     *         description="The mediatypeId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Mediatype object",
     *         @OA\JsonContent(ref="#/components/schemas/MediatypeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Mediatype",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MediatypeResponse")
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
    public function update(StoreMediatype $request, $id)
    {
        return $this->jsonResponse(['success' => (bool)Mediatype::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/mediatypes/{mediatypeId}",
     *     summary="Remove mediatype",
     *     operationId="destroy-mediatype",
     *     tags={"Mediatype"},
     *     @OA\Parameter(
     *         name="mediatypeId",
     *         in="path",
     *         description="The mediatypeId parameter in path",
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
    public function destroy(DestroyMediatype $request, $id)
    {
        try {
            Mediatype::find($id)->delete();
            return $this->jsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Seems like this genre is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
