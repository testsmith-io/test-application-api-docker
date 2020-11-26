<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\DestroyCustomer;
use App\Http\Requests\StoreCustomer;
use App\Http\Requests\UpdateCustomer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{

    /**
     * Create a new AuthController.php instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:customer', ['except' => ['login', 'store']]);
        $this->middleware('assign.guard:customer');
    }

    /**
     * @OA\Get(
     *     path="/customers",
     *     summary="Retrieve all customers",
     *     operationId="get-all-customers",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page, default=1",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
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
        return $this->jsonResponse(Customer::with('supportRep')->paginate());
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
        $input = $request->all();

        // Hash the password
        $input['password'] = app('hash')->make($input['password']);
        return $this->jsonResponse(['customer' => Customer::create($input)], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/customers/login",
     *     summary="Login customer",
     *     operationId="login-customer",
     *     tags={"Customer"},
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
     *     path="/customers/me",
     *     summary="Retrieve current customer info",
     *     operationId="get-current-customer-info",
     *     tags={"Customer"},
     *     @OA\Response(
     *         response=200,
     *         description="A customer",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResponse"),
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
     *     path="/customers/logout",
     *     summary="Destroy token",
     *     operationId="destroy token",
     *     tags={"Customer"},
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
     *     path="/customers/refresh",
     *     summary="Refresh token",
     *     operationId="get-new-token",
     *     tags={"Customer"},
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
        return $this->jsonResponse(Customer::with('supportRep')->findOrFail($id));
    }

    /**
     * @OA\Get(
     *     path="/customers/search",
     *     summary="Retrieve specific customers matching the search query",
     *     operationId="search-customer",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="The query parameter in path",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All matching customers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerResponse")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function search(Request $request)
    {
        $q = $request->get('q');

        return $this->jsonResponse(Customer::with('supportRep')->where('firstname','like',"%{$q}%")
            ->orWhere('lastname','like',"%{$q}%")
            ->orWhere('company','like',"%{$q}%")
            ->orWhere('city','like',"%{$q}%")->get());
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
