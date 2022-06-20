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
            foreach($manuals as $manual){
                if((auth()->user())){
                    $this->validateErrorOrSuccess($manuals, $manual->categories, $manual->tags);
                }
                foreach($manual->categories as $category){
                    //$category = $manual->categories->where('status', '');
                    $this->validateErrorOrSuccess($category);
                    // if($category->status == 'A' &&  !is_object($category)){
                    //     
                    // }
                }
                //$this->validateErrorOrSuccess($manuals, $manual->categories->where('status', 'A'), $manual->tags);
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
            $this->validateErrorOrSuccess($manual, $manual->categories);
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