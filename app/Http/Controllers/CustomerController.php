<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\DestroyCustomer;
use App\Http\Requests\StoreCustomer;
use App\Http\Requests\UpdateCustomer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class CustomerController extends Controller
{

    /**
     * @OA\Get(
     *     path="/customers",
     *     summary="Retrieve all customers",
     *     operationId="get-all-customers",
     *     tags={"Customer"},
     *     @OA\Response(
     *         response=200,
     *         description="All customers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return $this->jsonResponse(Customer::with('supportRep')->get());
    }

    /**
     * @OA\Post(
     *     path="/customers",
     *     summary="New customer",
     *     operationId="store-customer",
     *     tags={"Customer"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Customer object",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A customer",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreCustomer $request)
    {
        return $this->jsonResponse(['customer' => Customer::create($request->all())], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/customers/{customerId}",
     *     summary="Retrieve a specific customer",
     *     operationId="get-customer",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         description="The customerId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A customer",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return $this->jsonResponse(Customer::with('supportRep')->find($id));
    }

    /**
     * @OA\Put(
     *     path="/customers/{customerId}",
     *     summary="Update customer",
     *     operationId="update-customer",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         description="The customerId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Customer object",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A customer",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerResponse")
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
    public function update(UpdateCustomer $request, $id)
    {
        return $this->jsonResponse(['success' => (bool)Customer::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/customers/{customerId}",
     *     summary="Remove customer",
     *     operationId="destroy-customer",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         description="The customerId parameter in path",
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
    public function destroy(DestroyCustomer $request, $id)
    {
        try {
            Customer::find($id)->delete();
            return $this->jsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Seems like this customer is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
