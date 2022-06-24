<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new FileService();
        parent::__construct();
    }

    public function index()
    {
        try {
            $files = $this->service->getAll();
            if (!is_object($files)) {
                throw new \Exception($files);
            }
            $this->validateErrorOrSuccess($files);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $file = $this->service->create($request);
            if (!is_object($file)) {
                throw new \Exception($file);
            }
            $this->validateErrorOrSuccess($file);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function getById($id)
    {
        try {
            $file = $this->service->getId($id);
            if (!is_object($file)) {
                throw new \Exception($file);
            }
            $this->validateErrorOrSuccess($file);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $file = $this->service->update($request, $id);
            if (!is_object($file)) {
                throw new \Exception($file);
            }
            $this->validateErrorOrSuccess($file);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function delete(Request $request, $id)
    {
        try {
            $file = $this->service->delete($request, $id);
            if (!is_object($file)) {
                throw new \Exception($file);
            }
            $this->validateErrorOrSuccess($file);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }
}