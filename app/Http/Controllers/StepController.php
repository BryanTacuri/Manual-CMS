<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStepsRequest;
use App\Http\Requests\UpdateStepsRequest;
use App\Models\Steps;
use App\Services\StepService;
use Illuminate\Http\Request;


class StepController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new StepService();
        parent::__construct();
    }

    public function index()
    {
        try {
            $steps = $this->service->getAll();
            if (!is_object($steps)) {
                throw new \Exception($steps);
            }
            foreach ($steps as $step) {
                $step->files = $this->getElements($step, 'files');
                $step->tags = $this->getElements($step, 'tags');
                $this->validateErrorOrSuccess($steps, $step->files, $step->tags);
            }
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $step = $this->service->create($request);
            if (!is_object($step)) {
                throw new \Exception($step);
            }
            $this->validateErrorOrSuccess($step, $step->tags, $step->files);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function getById($id)
    {
        try {
            $step = $this->service->getId($id);
            if (!is_object($step)) {
                throw new \Exception($step);
            }
            $step->files = $this->getElements($step, 'files');
            $step->tags = $this->getElements($step, 'tags');
            $this->validateErrorOrSuccess($step, $step->files, $step->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $step = $this->service->update($request, $id);
            if (!is_object($step)) {
                throw new \Exception($step);
            }
            $step->files = $this->getElements($step, 'files');
            $step->tags = $this->getElements($step, 'tags');
            $this->validateErrorOrSuccess($step, $step->files, $step->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function delete(Request $request, $id)
    {
        try {
            $step = $this->service->delete($request, $id);
            if (!is_object($step)) {
                throw new \Exception($step);
            }
            $step->files = $this->getElements($step, 'files');
            $step->tags = $this->getElements($step, 'tags');
            $this->validateErrorOrSuccess($step, $step->files, $step->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }
}