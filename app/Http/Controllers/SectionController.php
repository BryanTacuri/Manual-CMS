<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Section;


use App\Services\SectionService;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new SectionService();
        parent::__construct();
    }

    public function index()
    {
        try {
            $sections = $this->service->getAll();

            foreach ($sections as $manual) {
                $manual->categories = $this->getElements($manual, 'categories');
                $manual->tags = $this->getElements($manual, 'tags');

                $this->validateErrorOrSuccess($sections, $manual->categories, $manual->tags);
            }
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $section = $this->service->create($request);
            if (!is_object($section)) {
                throw new \Exception($section);
            }

            $this->validateErrorOrSuccess($section, $section->categories, $section->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function getById($id)
    {
        try {
            $section = $this->service->getId($id);
            if (!is_object($section)) {
                throw new \Exception($section);
            }
            $section->categories = $this->getElements($section, 'categories');
            $section->tags = $this->getElements($section, 'tags');
            $this->validateErrorOrSuccess($section, $section->categories, $section->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $section = $this->service->update($request, $id);
            if (!is_object($section)) {
                throw new \Exception($section);
            }
            $section->categories = $this->getElements($section, 'categories');
            $section->tags = $this->getElements($section, 'tags');
            $this->validateErrorOrSuccess($section, $section->categories, $section->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }

    public function delete(Request $request, $id)
    {
        try {
            $section = $this->service->delete($request, $id);
            if (!is_object($section)) {
                throw new \Exception($section);
            }
            $section->categories = $this->getElements($section, 'categories');
            $section->tags = $this->getElements($section, 'tags');
            $this->validateErrorOrSuccess($section, $section->categories, $section->tags);
        } catch (\Exception $e) {
            $this->setMessageError($e->getMessage());
        }
        return $this->returnData();
    }
}