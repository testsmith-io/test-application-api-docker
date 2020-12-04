<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyInvoiceline;
use App\Http\Requests\StoreInvoiceline;
use App\Invoiceline;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class InvoicelineController extends Controller
{

    /**
     * @OA\Get(
     *     path="/invoicelines",
     *     summary="Retrieve all invoicelines",
     *     operationId="get-all-invoicelines",
     *     tags={"Invoiceline"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page, default=1",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All invoiceline",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/InvoicelineResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return $this->preferredFormat(Invoiceline::paginate());
    }

    /**
     * @OA\Post(
     *     path="/invoicelines",
     *     summary="New invoicelines",
     *     operationId="store-invoiceline",
     *     tags={"Invoiceline"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Invoiceline object",
     *         @OA\JsonContent(ref="#/components/schemas/InvoicelineRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Album",
     *         @OA\JsonContent(ref="#/components/schemas/InvoicelineResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreInvoiceline $request)
    {
        return $this->preferredFormat(['invoiceline' => Invoiceline::create($request->all())], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/invoicelines/{invoicelineId}",
     *     summary="Retrieve a specific invoiceline",
     *     operationId="get-invoiceline",
     *     tags={"Invoiceline"},
     *     @OA\Parameter(
     *         name="invoicelineId",
     *         in="path",
     *         description="The invoicelineId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An invoiceline",
     *         @OA\JsonContent(ref="#/components/schemas/InvoicelineResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return $this->preferredFormat(Invoiceline::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/invoicelines/{invoicelineId}",
     *     summary="Update invoiceline",
     *     operationId="update-invoiceline",
     *     tags={"Invoiceline"},
     *     @OA\Parameter(
     *         name="invoicelineId",
     *         in="path",
     *         description="The invoicelineId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Invoiceline object",
     *         @OA\JsonContent(ref="#/components/schemas/InvoicelineRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An invoiceline",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/InvoicelineResponse")
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
    public function update(StoreInvoiceline $request, $id)
    {
        return $this->preferredFormat(['success' => (bool)Invoiceline::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/invoicelines/{invoicelineId}",
     *     summary="Remove invoiceline",
     *     operationId="destroy-invoiceline",
     *     tags={"Invoiceline"},
     *     @OA\Parameter(
     *         name="invoicelineId",
     *         in="path",
     *         description="The invoicelineId parameter in path",
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
    public function destroy(DestroyInvoiceline $request, $id)
    {
        try {
            Invoiceline::find($id)->delete();
            return $this->preferredFormat(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->preferredFormat([
                    'success' => false,
                    'message' => 'Seems like this invoice is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
