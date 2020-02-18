<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Http\Requests\DestroyEmployee;
use App\Http\Requests\StoreEmployee;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/employees",
     *     summary="Retrieve all employees",
     *     operationId="get-all-employees",
     *     tags={"Employee"},
     *     @OA\Response(
     *         response=200,
     *         description="All employees",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/EmployeeResponse")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        return $this->jsonResponse(Employee::with('reportsTo', 'customers')->get());
    }

    /**
     * @OA\Post(
     *     path="/employees",
     *     summary="New employee",
     *     operationId="store-employee",
     *     tags={"Employee"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Employee object",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Employee",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function store(StoreEmployee $request)
    {
        return $this->jsonResponse(['employee' => Employee::create($request->all())], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/employees/{employeeId}",
     *     summary="Retrieve a specific employee",
     *     operationId="get-employee",
     *     tags={"Employee"},
     *     @OA\Parameter(
     *         name="employeeId",
     *         in="path",
     *         description="The employeeId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Employee",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function show($id)
    {
        return $this->jsonResponse(Employee::with('reportsTo', 'customers')->find($id));
    }

    /**
     * @OA\Put(
     *     path="/employees/{employeeId}",
     *     summary="Update employee",
     *     operationId="update-employee",
     *     tags={"Employee"},
     *     @OA\Parameter(
     *         name="employeeId",
     *         in="path",
     *         description="The employeeId parameter in path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Employee object",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An Employee",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/EmployeeResponse")
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
    public function update(StoreEmployee $request, $id)
    {
        return $this->jsonResponse(['success' => (bool)Employee::where('id', $id)->update($request->all())], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/employees/{employeeId}",
     *     summary="Remove employee",
     *     operationId="destroy-employee",
     *     tags={"Employee"},
     *     @OA\Parameter(
     *         name="employeeId",
     *         in="path",
     *         description="The employeeId parameter in path",
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
    public function destroy(DestroyEmployee $request, $id)
    {
        try {
            Employee::find($id)->delete();
            return $this->jsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Seems like this employee is used elsewhere.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
