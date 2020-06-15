<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Http\Requests\DestroyEmployee;
use App\Http\Requests\StoreEmployee;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{

    /**
     * Create a new EmployeeController.php instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:employee', ['except' => ['login', 'store']]);
        $this->middleware('assign.guard:employee');
    }

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
        $input = $request->all();

        // Hash the password
        $input['password'] = app('hash')->make($input['password']);
        return $this->jsonResponse(['employee' => Employee::create($input)], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/employees/login",
     *     summary="Login employee",
     *     operationId="login-employee",
     *     tags={"Employee"},
     *    	@OA\RequestBody(
     *    		@OA\MediaType(
     *                mediaType="application/json",
     *    			@OA\Schema(
     *    				 @OA\Property(property="email",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    ),
     *    				 @OA\Property(property="password",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A token",
     *    		@OA\MediaType(
     *                mediaType="application/json",
     *    			@OA\Schema(
     *    				 @OA\Property(property="access_token",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    ),
     *    				 @OA\Property(property="token_type",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    ),
     *    				 @OA\Property(property="expires_in",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    )
     *             )
     *         )
     *     )
     * )
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $credentials = $request->all(['email', 'password']);
        if (!$token = app('auth')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Get(
     *     path="/employees/me",
     *     summary="Retrieve current employee info",
     *     operationId="get-current-employee-info",
     *     tags={"Employee"},
     *     @OA\Response(
     *         response=200,
     *         description="A employee",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function me()
    {
        return response()->json(app('auth')->user());
    }

    /**
     * @OA\Get(
     *     path="/employees/logout",
     *     summary="Destroy token",
     *     operationId="destroy-token-employee",
     *     tags={"Employee"},
     *     @OA\Response(
     *         response=200,
     *         description="A token",
     *    		@OA\MediaType(
     *                mediaType="application/json",
     *    			@OA\Schema(
     *    				 @OA\Property(property="message",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    ),
     *             )
     *         )
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function logout()
    {
        app('auth')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Get(
     *     path="/employees/refresh",
     *     summary="Refresh token",
     *     operationId="get-new-token-employee",
     *     tags={"Employee"},
     *     @OA\Response(
     *         response=200,
     *         description="A token",
     *    		@OA\MediaType(
     *                mediaType="application/json",
     *    			@OA\Schema(
     *    				 @OA\Property(property="access_token",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    ),
     *    				 @OA\Property(property="token_type",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    ),
     *    				 @OA\Property(property="expires_in",
     *                        type="string",
     *                        example="",
     *                        description=""
     *                    )
     *             )
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(app('auth')->refresh());
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
