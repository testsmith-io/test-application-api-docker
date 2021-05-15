<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyInvoice;
use App\Http\Requests\StoreInvoice;
use App\Invoice;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{

    /**
     * Create a new InvoiceController.php instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * @OA\Get(
     *     path="/invoices",
     *     summary="Retrieve all invoices",
     *     operationId="get-all-invoices",
     *     tags={"Invoice"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page, default=1",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All invoices",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/InvoiceResponse")
     *         ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function index()
    {
        return $this->preferredFormat(Invoice::where('customer_id', app('auth')->user()->id)->paginate());
    }

    /**
     * @OA\Post(
     *     path="/invoices",
     *     summary="New invoice",
     *     operationId="store-invoice",
     *     tags={"Invoice"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Invoice object",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An invoice",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceResponse"),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreInvoice $request)
    {
        return $this->preferredFormat(Invoice::create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/invoices/{invoiceId}",
     *     summary="Retrieve a specific invoice",
     *     operationId="get-invoice",
     *     tags={"Invoice"},
     *     @OA\Parameter(
     *         name="invoiceId",
     *         in="path",
     *         description="The invoiceId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An invoice",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function show($id)
    {
        return $this->preferredFormat(Invoice::where('id', $id)->where('customer_id', app('auth')->user()->id)->first());
    }

    /**
     * @OA\Put(
     *     path="/invoices/{invoiceId}",
     *     summary="Update invoice",
     *     operationId="update-invoice",
     *     tags={"Invoice"},
     *     @OA\Parameter(
     *         name="invoiceId",
     *         in="path",
     *         description="The invoiceId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Invoice object",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An invoice",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/InvoiceResponse")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     * @param Request $request
     * @return array
     */
    public function update(StoreInvoice $request, $id)
    {
        return $this->preferredFormat(['success' => (bool)Invoice::where('id', $id)->where('customer_id', app('auth')->user()->id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/invoices/{invoiceId}",
     *     summary="Remove invoice",
     *     operationId="destroy-invoice",
     *     tags={"Invoice"},
     *     @OA\Parameter(
     *         name="invoiceId",
     *         in="path",
     *         description="The invoiceId parameter in path",
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
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function destroy(DestroyInvoice $request, $id)
    {
        try {
            Invoice::find($id)->where('customer_id', app('auth')->user()->id)->delete();
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
