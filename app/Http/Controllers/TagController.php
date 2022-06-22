<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
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

    public function index()
    {
        try {
            $tags = $this->service->getAll();
            $this->validateErrorOrSuccess($tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $tags = $this->service->create($request);
            $this->validateErrorOrSuccess($tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }


    public function getById($id)
    {
        try {
            $tag = $this->service->getId($id);
            $this->validateErrorOrSuccess($tag);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {

        try {
            $tag = $this->service->update($request, $id);
            $this->validateErrorOrSuccess($tag);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }


    public function delete(Request $request, $id)
    {
        try {
            $tag = $this->service->delete($request, $id);
            $this->validateErrorOrSuccess($tag);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
    }
}