<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Manual;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;

class ManualService
{

    public function getAll()
    {
        try {
            $manuals = Manual::all();
            if (count($manuals) == 0) {
                throw new \Exception('No hay manuales');
            }
            if ((auth()->user())) {
                return Manual::latest('id')->paginate(10);
            }
            return Manual::where('status', 'A')->latest('id')->paginate(10);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getId($id)
    {
        try {
            $manual = Manual::find($id);
            if ($manual == null) {
                throw new \Exception('No existe este manual');
            }
            if ((auth()->user())) {
                return $manual;
            }
            if ($manual->status != 'A') {
                throw new \Exception('El manual no está activo');
            }
            return $manual;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create($data)
    {
        try {
            $validator = Validator::make($data->all(), [
                'title' => 'required|string|max:50',
                'description' => 'required|string|max:255',
                'status' => 'required|string|max:1',
                'user_create' => 'required'
            ]);
            if ($validator->fails()) {
                $jsonErrors = $validator->errors();
                $error = json_encode($jsonErrors, TRUE);
                throw new \Exception($error);
                dd($error);
            }
            $manual = new Manual();
            $manual->title = $data->title;
            $manual->description = $data->description;
            $manual->status = $data->status;
            $manual->user_create = $data->user_create;
            $manual->save();

            $this->actionElements($manual, $data, 'categories', new Category(), 'create');

            $this->actionElements($manual, $data, 'tags', new Tag(), 'create');

            return $manual;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($data, $id)
    {
        try {
            $validator = Validator::make($data->all(), [
                'title' => 'required|string|max:50',
                'description' => 'required|string|max:255',
                'status' => 'required|string|max:1',
                'user_modifies' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);
                throw new \Exception($error);
            }
            $manual = Manual::find($id);
            if ($manual == null) {
                throw new \Exception('No existe este manual');
            }
            $manual->title = $data->title;
            $manual->description = $data->description;
            $manual->status = $data->status;
            $manual->user_create = $data->user_create;
            $manual->user_modifies = $data->user_modifies;

            $manual->categories()->detach();
            $this->actionElements($manual, $data, 'categories', new Category(), 'update');

            $manual->tags()->detach();
            $this->actionElements($manual, $data, 'tags', new Tag(), 'update');

            $manual->update();
            return $manual;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function actionElements($manual, $data, $elementActions, $model, $action)
    {
        try {
            foreach ($data->$elementActions as $name) {
                $element = $model::where('name', $name)->where('status', 'A')->first();
                if ($element != null && $action == 'create') {
                    $manual->$elementActions()->attach($element->id, ['user_create' => $data->user_create]);
                }
                if ($element != null && $action == 'update') {
                    $manual->$elementActions()->attach($element->id, ['user_create' => $data->user_create, 'user_modifies' => $data->user_modifies]);
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|max:1',
                'user_delete' => 'required',
                'date_delete' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);
                throw new \Exception($error);
            }
            $manual = Manual::find($id);
            if ($manual == null) {
                throw new \Exception('No existe este manual');
            }
            $manual->update($request->only('status', 'user_delete', 'date_delete'));
            return $manual;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSectionOfManual($id)
    {
        try {
            $manual = Manual::find($id);
            if ($manual == null) {
                throw new \Exception('No existe este manual');
            }
            return $manual->sections;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}