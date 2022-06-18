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

    private $service;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->service = new CategoryService();
    }

    public function index()
    {
        try {
            $categories = $this->service->getAll();
            if (is_object($categories)) {
                return response()->json(new ApiResponse($categories, 200, 'Se ha listado correctamente'), 200);
            } else {
                return response()->json(new ApiResponse(null, 404, 'No se ha encontrado ninguna categoria'), 404);
            }
        } catch (\Exception $e) {
            return response()->json(new ApiResponse(null, 500, $e->getMessage()), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $categories = $this->service->create($request);
            if (is_object($categories) && is_array($categories)) {
                return response()->json(new ApiResponse($categories, 200, 'Se ha procesado correctamente'), 200);
            } else {
                return response()->json(new ApiResponse(null, 404, $categories), 404);
            }
        } catch (\Exception $e) {
            return response()->json(new ApiResponse(null, 500, $e->getMessage()), 500);
        }
    }


    public function getById($id)
    {
        try {
            $category = $this->service->getId($id);
            if (is_object($category)) {
                return response()->json(new ApiResponse($category, 200, 'Se ha obtenido correctamente'), 200);
            } else {
                return response()->json(new ApiResponse(null, 404, 'ERROR: No se ha encontrado la categoria'), 404);
            }
        } catch (\Exception $e) {
            return response()->json(new ApiResponse(null, 500, $e->getMessage()), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = $this->service->update($request, $id);
            if (is_object($category)) {
                return response()->json(new ApiResponse($category, 200, 'Se ha actualizado correctamente'), 200);
            } else {
                return response()->json(new ApiResponse(null, 404, $category), 404);
            }
        } catch (\Exception $e) {
            return response()->json(new ApiResponse(null, 500, $e->getMessage()), 500);
        }
    }


    public function delete(Request $request, $id)
    {
        try {
            $category = $this->service->delete($request, $id);
            if (is_object($category)) {
                return response()->json(new ApiResponse($category, 200, 'Se ha eliminado correctamente'), 200);
            } else {
                return response()->json(new ApiResponse(null, 404, $category), 404);
            }
        } catch (\Exception $e) {
            return response()->json(new ApiResponse(null, 500, $e->getMessage()), 500);
        }
    }
}