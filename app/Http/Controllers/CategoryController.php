<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    //1. Borrar el metodo validateErrorOrSuccess
    //2. Baregar setdatacorrect
    //3. agregar metodo setError en el ctac

    private $service;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new CategoryService();
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $categories = $this->service->getAll($request);
            $this->setDataCorrect($categories, 'Categorias encontradas', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $categories = $this->service->create($request);
            $this->setDataCorrect($categories, 'Categoria  guardada', 201);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }


    public function getById($id)
    {
        try {
            $category = $this->service->getId($id);
            $this->setDataCorrect($category, 'Categoria  encontrada', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {

        try {
            $category = $this->service->update($request, $id);
            $this->setDataCorrect($category, 'Categoria  actualizada', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), 500);
        }
        return $this->returnData();
    }


    public function delete(Request $request, $id)
    {
        try {
            $category = $this->service->delete($request, $id);
            $this->setDataCorrect($category, 'Categoria  eliminada', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), 500);
        }
    }
}