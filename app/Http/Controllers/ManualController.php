<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManualRequest;
use App\Http\Requests\UpdateManualRequest;
use App\Models\Manual;
use App\Services\ManualService;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new ManualService();
    }

    public function index()
    {
        try {
            $manuals = $this->service->getAll();

            foreach($manuals as $manual){
                $apiResponse = new ApiResponse($manuals, $manual->categories, $manual->tags);
            }

            if($manuals->count()){
                $apiResponse->message = "Se ha obtenido correctamente la lista de manuales"; 
            }else{
                $apiResponse->message = "No se han encontrado datos del manual";
            }

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
            $manuals = $this->service->create($request);
            $apiResponse = new ApiResponse($manuals, $manuals->categories);
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
            $manual = $this->service->getId($id);
            $apiResponse = new ApiResponse($manual, $manual->categories);
            if($manual->count()){
                $apiResponse->message = "Se ha obtenido correctamente el manual"; 
            }else{
                $apiResponse->message = "No se han encontrado datos del manual";
            }
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
            $manual = $this->service->update($request, $id);
            $apiResponse = new ApiResponse($manual, $manual->categories);
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
            $manual = $this->service->delete($request, $id);
            $apiResponse = new ApiResponse($manual);
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