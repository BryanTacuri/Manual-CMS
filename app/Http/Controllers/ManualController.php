<?php

namespace App\Http\Controllers;

use App\Services\ManualService;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new ManualService();
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $manuals = $this->service->getAll($request);
            $this->setDataCorrect($manuals, 'Manuales encontradas', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $manual = $this->service->create($request);
            $this->setDataCorrect($manual, 'Manual creado correctamente', 201);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function getById(Request $request, $id)
    {
        try {
            $manual = $this->service->getId($request, $id);
            $this->setDataCorrect($manual, 'Manual encontrado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $manual = $this->service->update($request, $id);
            $this->setDataCorrect($manual, 'Manual actualizado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function delete(Request $request, $id)
    {
        try {
            $manual = $this->service->delete($request, $id);
            $this->setDataCorrect($manual, 'Manual eliminado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }
}