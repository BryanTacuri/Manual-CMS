<?php

namespace App\Services;

use App\Models\File;
use App\Models\Step;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;


class StepService
{
    public function getAll()
    {
        try {
            $steps = Step::all();
            if (count($steps) == 0) {
                throw new \Exception('No hay secciones');
            }
            if ((auth()->user())) {
                return Step::paginate();
            } else {
                return Step::where('status', 'A')->paginate(10);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create($data)
    {
        try {
            $validator = Validator::make($data->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|string|max:1',
                'subsection_id' => 'required',
                'user_create' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);

                throw new \Exception($error);
            }
            $step = new Step();
            $step->title = $data->title;
            $step->description = $data->description;
            $step->status = $data->status;
            $step->subsection_id = $data->subsection_id;
            $step->user_create = $data->user_create;
            $step->save();

            $this->actionElements($step, $data, 'files', new File(), 'create');

            $this->actionElements($step, $data, 'tags', new Tag(), 'create');
            return $step;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getId($id)
    {
        try {
            $step = Step::find($id);
            if ($step == null) {
                throw new \Exception('No existe ese paso');
            }
            if ((auth()->user())) {
                return $step;
            } else {
                if ($step->status == 'A') {
                    return $step;
                } else {
                    throw new \Exception('No existe ese paso');
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function update($data, $id)
    {
        try {
            $validator = Validator::make($data->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|string|max:1',
                'subsection_id' => 'required',
                'user_modifies' => 'required',

            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors);

                throw new \Exception($error);
            }

            $step = Step::find($id);
            if ($step == null) {
                throw new \Exception('No existe este step');
            }
            $step = new Step();
            $step->title = $data->title;
            $step->description = $data->description;
            $step->status = $data->status;
            $step->subsection_id = $data->subsection_id;
            $step->user_modifies = $data->user_modifies;


            $step->categories()->detach();
            $this->actionElements($step, $data, 'categories', new File(), 'update');

            $step->tags()->detach();
            $this->actionElements($step, $data, 'tags', new Tag(), 'update');

            $step->update();
            return $step;
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
            $step = Step::find($id);
            if ($step == null) {
                throw new \Exception('No existe este step');
            }
            $step->update($request->only('status', 'user_delete', 'date_delete'));
            return $step;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function actionElements($section, $data, $elementActions, $model, $action)
    {
        try {
            foreach ($data->$elementActions as $name) {
                $element = $model::where('name', $name)->where('status', 'A')->first();
                if ($element != null && $action == 'create') {
                    $section->$elementActions()->attach($element->id, ['user_create' => $data->user_create]);
                }
                if ($element != null && $action == 'update') {
                    $section->$elementActions()->attach($element->id, ['user_create' => $data->user_create, 'user_modifies' => $data->user_modifies]);
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}