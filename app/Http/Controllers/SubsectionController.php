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

    public function index()
    {
        try {
            $subsections = $this->service->getAll();
            if (!is_object($subsections)) {
                throw new \Exception($subsections);
            }
            foreach ($subsections as $subsection) {
                $subsection->tags = $this->getElements($subsection, 'tags');
                $this->validateErrorOrSuccess($subsections, $subsection->tags);
            }
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $subsection = $this->service->create($request);
            if (!is_object($subsection)) {
                throw new \Exception($subsection);
            }
            $this->validateErrorOrSuccess($subsection, $subsection->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function getById($id)
    {
        try {
            $subsection = $this->service->getId($id);
            if (!is_object($subsection)) {
                throw new \Exception($subsection);
            }
            $subsection->tags = $this->getElements($subsection, 'tags');
            $this->validateErrorOrSuccess($subsection, $subsection->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $subsection = $this->service->update($request, $id);
            if (!is_object($subsection)) {
                throw new \Exception($subsection);
            }
            $subsection->tags = $this->getElements($subsection, 'tags');
            $this->validateErrorOrSuccess($subsection, $subsection->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function delete(Request $request, $id)
    {
        try {
            $subsection = $this->service->delete($request, $id);
            if (!is_object($subsection)) {
                throw new \Exception($subsection);
            }
            $subsection->tags = $this->getElements($subsection, 'tags');
            $this->validateErrorOrSuccess($subsection, $subsection->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }
}