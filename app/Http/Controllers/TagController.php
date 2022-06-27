<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TagService;

class TagController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new TagService();
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $tags = $this->service->getAll($request);
            $this->setDataCorrect($tags, 'Tags encontradas', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $tag = $this->service->create($request);
            $this->setDataCorrect($tag, 'Tag creado correctamente', 201);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }


    public function getById(Request $request, $id)
    {
        try {
            $tag = $this->service->getId($request, $id);
            $this->setDataCorrect($tag, 'Tag encontrado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {

        try {
            $tag = $this->service->update($request, $id);
            $this->setDataCorrect($tag, 'Tag actualizado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }


    public function delete(Request $request, $id)
    {
        try {
            $tag = $this->service->delete($request, $id);
            $this->setDataCorrect($tag, 'Tag eliminado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }
}