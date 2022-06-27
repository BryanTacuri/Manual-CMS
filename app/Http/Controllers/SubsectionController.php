<?php

namespace App\Http\Controllers;

use App\Services\SubsectionService;
use Illuminate\Http\Request;

class SubsectionController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new SubsectionService();
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $subsections = $this->service->getAll($request);
            $this->setDataCorrect($subsections, 'Subsections encontradas', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $subsection = $this->service->create($request);
            $this->setDataCorrect($subsection, 'Subsection creado correctamente', 201);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function getById(Request $request, $id)
    {
        try {
            $subsection = $this->service->getId($request, $id);
            $this->setDataCorrect($subsection, 'Subsection encontrado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $subsection = $this->service->update($request, $id);
            $this->setDataCorrect($subsection, 'Subsection actualizado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function delete(Request $request, $id)
    {
        try {
            $subsection = $this->service->delete($request, $id);
            $this->setDataCorrect($subsection, 'Subsection eliminado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }
}