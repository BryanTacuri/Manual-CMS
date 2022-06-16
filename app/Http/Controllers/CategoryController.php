<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CategoryService;


class CategoryController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    private $service;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store', 'index', 'getById', 'update', 'delete']]);
        $this->service = new CategoryService();
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        try {
            $categories = $this->service->getAll();
            $apiResponse = new ApiResponse($categories);
            $apiResponse->message = 'Se ha obtenido correctamente la lista de categorias';
            $apiResponse->statusCode = 200;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        } catch (\Exception $e) {
            $apiResponse = new ApiResponse();
            $apiResponse->message = $e->getMessage();
            $apiResponse->statusCode = 500;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        }
    }
    public function store(Request $request)
    {
        try {
            $categories = $this->service->create($request);
            // $input['usuario_id'] = $_REQUEST['usuario_id']; ???

            $apiResponse = new ApiResponse($categories);
            $apiResponse->message = 'Se ha creado correctamente';
            $apiResponse->statusCode = 200;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        } catch (\Exception $e) {
            $apiResponse = new ApiResponse();
            $apiResponse->message = $e->getMessage();
            $apiResponse->statusCode = 500;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        }
    }


    public function getById($id)
    {
        try {
            $category = $this->service->getId($id);
            $apiResponse = new ApiResponse($category);
            $apiResponse->message = 'Se ha obtenido correctamente la categoria';
            $apiResponse->statusCode = 200;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        } catch (\Exception $e) {
            $apiResponse = new ApiResponse();
            $apiResponse->message = $e->getMessage();
            $apiResponse->statusCode = 500;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = $this->service->update($request, $id);
            $apiResponse = new ApiResponse($category);
            $apiResponse->message = 'Se ha actualizado correctamente';
            $apiResponse->statusCode = 200;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        } catch (\Exception $e) {
            $apiResponse = new ApiResponse();
            $apiResponse->message = $e->getMessage();
            $apiResponse->statusCode = 500;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        }
    }


    public function delete(Request $request, $id)
    {
        try {
            $category = $this->service->delete($request, $id);
            $apiResponse = new ApiResponse($category);
            $apiResponse->message = 'Se ha eliminado correctamente';
            $apiResponse->statusCode = 200;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        } catch (\Exception $e) {
            $apiResponse = new ApiResponse();
            $apiResponse->message = $e->getMessage();
            $apiResponse->statusCode = 500;
            return Response()->json($apiResponse, $apiResponse->statusCode);
        }
    }
}