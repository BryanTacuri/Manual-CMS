<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Manual;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
                'description' => 'required|string|max:255'
            ]);
            if ($validator->fails()) {
                $jsonErrors = $validator->errors();
                $error = json_encode($jsonErrors, TRUE);
                throw new \Exception($error);
            }
            $manual = new Manual();
            $manual->title = $data->title;
            $manual->description = $data->description;
            $manual->user_create = auth()->user()->id;
            $manual->save();

            $this->actionElements($manual->categories(), $data->categories, new Category(), 'create');

            $this->actionElements($manual->tags(), $data->tags, new Tag(), 'create');

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
                'status' => 'required|string|max:1'
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
            $manual->user_modifies = auth()->user()->id;

            $manual->categories()->detach();
            $this->actionElements($manual->categories(), $data->categories, new Category(), 'update');

            $manual->tags()->detach();
            $this->actionElements($manual->tags(), $data->tags, new Tag(), 'update');

            $manual->update();
            return $manual;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($request, $id)
    {
        try {
            $manual = Manual::find($id);
            if ($manual == null) {
                throw new \Exception('No existe este manual');
            }
            $manual->status = 'E';
            $manual->user_delete = auth()->user()->id;
            $manual->date_delete = Carbon::now();
            $manual->update($request->only('status', 'user_delete', 'date_delete'));
            return $manual;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function actionElements($manual, $data, $model, $action)
    {
        try {
            foreach ($data as $name) {
                $element = $model::where('name', $name)->where('status', 'A')->first();
                if ($element != null && $action == 'create') {
                    $manual->attach($element->id, ['user_create' => auth()->user()->id]);
                }
                if ($element != null && $action == 'update') {
                    $manual->attach($element->id, ['user_create' => auth()->user()->id, 'user_modifies' => auth()->user()->id]);
                }
            }
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
            if(count($manual->sections) == 0) {
                throw new \Exception('No hay secciones');
            }
            return $manual->sections;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}