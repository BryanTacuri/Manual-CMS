<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Controllers\ManualCMSController;

class CategoryController extends Controller
{

    private $service;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new CategoryService();
        parent::__construct();
    }

    public function index()
    {
        try {
            $categories = $this->service->getAll();
            $this->validateErrorOrSuccess($categories);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $categories = $this->service->create($request);
            $this->validateErrorOrSuccess($categories);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }


    public function getById($id)
    {
        try {
            $category = $this->service->getId($id);
            $this->validateErrorOrSuccess($category);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {

        try {
            $category = $this->service->update($request, $id);
            $this->validateErrorOrSuccess($category);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }


    public function delete(Request $request, $id)
    {
        try {
            $category = $this->service->delete($request, $id);
            $this->validateErrorOrSuccess($category);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
    }
}