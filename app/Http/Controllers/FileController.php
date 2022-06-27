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

    public function index(Request $request)
    {
        try {
            $files = $this->service->getAll($request);
            $this->setDataCorrect($files, 'Files encontradas', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $file = $this->service->create($request);
            $this->setDataCorrect($file, 'File creado correctamente', 201);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function getById(Request $request, $id)
    {
        try {
            $file = $this->service->getId($request, $id);
            $this->setDataCorrect($file, 'File encontrado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $file = $this->service->update($request, $id);
            $this->setDataCorrect($file, 'File actualizado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function delete(Request $request, $id)
    {
        try {
            $file = $this->service->delete($request, $id);
            $this->setDataCorrect($file, 'File eliminado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }
}