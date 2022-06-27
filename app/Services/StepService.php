<?php

namespace App\Services;

use App\Models\File;
use App\Models\Step;
use App\Models\Tag;
use Carbon\Carbon;
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
                'subsection_id' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);

                throw new \Exception($error);
            }
            $step = new Step();
            $step->title = $data->title;
            $step->description = $data->description;
            $step->subsection_id = $data->subsection_id;
            $step->user_create = auth()->user()->id;
            $step->save();

            $this->actionElements($step->tags(), $data->tags, new Tag(), 'create');

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
            $step->user_modifies = auth()->user()->id;



            $step->tags()->detach();
            $this->actionElements($step->tags(), $data->tags, new Tag(), 'update');

            $step->update();
            return $step;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {

            $step = Step::find($id);
            if ($step == null) {
                throw new \Exception('No existe este step');
            }
            $step->status = 'E';
            $step->user_delete = auth()->user()->id;
            $step->date_delete = Carbon::now();
            $step->update();
            return $step;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function actionElements($step, $data, $model, $action)
    {
        try {
            foreach ($data as $name) {
                $element = $model::where('name', $name)->where('status', 'A')->first();
                if ($element != null && $action == 'create') {
                    $step->attach($element->id, ['user_create' => auth()->user()->id]);
                }
                if ($element != null && $action == 'update') {
                    $step->attach($element->id, ['user_create' => auth()->user()->id, 'user_modifies' => auth()->user()->id]);
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}