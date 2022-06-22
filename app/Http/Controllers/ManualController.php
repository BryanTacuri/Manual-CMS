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

    public function index()
    {
        try {
            $manuals = $this->service->getAll();
            if (!is_object($manuals)) {
                throw new \Exception($manuals);
            }
            foreach ($manuals as $key => $manual) {
                $manual->categories = $this->service->getCategoryManual($manuals[$key]->id);
                $manual->tags = $this->service->getTagManual($manuals[$key]->id);
                $this->validateErrorOrSuccess($manuals, $manual->categories, $manual->tags);
            }
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $manual = $this->service->create($request);
            if (!is_object($manual)) {
                throw new \Exception($manual);
            }
            $this->validateErrorOrSuccess($manual, $manual->categories, $manual->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function getById($id)
    {
        try {
            $manual = $this->service->getId($id);
            if (!is_object($manual)) {
                throw new \Exception($manual);
            }
            $manual->categories = $this->service->getCategoryManual($manual->id);
            $this->validateErrorOrSuccess($manual, $manual->categories);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $manual = $this->service->update($request, $id);
            if (!is_object($manual)) {
                throw new \Exception($manual);
            }
            $this->validateErrorOrSuccess($manual, $manual->categories);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function delete(Request $request, $id)
    {
        try {
            $manual = $this->service->delete($request, $id);
            if (!is_object($manual)) {
                throw new \Exception($manual);
            }
            $this->validateErrorOrSuccess($manual, $manual->categories);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }
}