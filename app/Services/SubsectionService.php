<?php

namespace App\Services;

use App\Models\Subsection;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SubsectionService
{
    public function getAll()
    {
        try {
            $subsections = Subsection::all();
            if (count($subsections) == 0) {
                throw new \Exception('No hay subsecciones');
            }
            if ((auth()->user())) {
                return Subsection::latest('id')->paginate(10);
            }
            return Subsection::where('status', 'A')->latest('id')->paginate(10);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getId($id)
    {
        try {
            $subsection = Subsection::find($id);
            if ($subsection == null) {
                throw new \Exception('No existe esta subsección');
            }
            if ((auth()->user())) {
                return $subsection;
            }
            if ($subsection->status != 'A') {
                throw new \Exception('La subsección no está activo');
            }
            return $subsection;
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
                'section_id' => 'required'
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);
                throw new \Exception($error);
            }
            $subsection = new Subsection();
            $subsection->title = $data->title;
            $subsection->description = $data->description;
            $subsection->section_id = $data->section_id;
            $subsection->status = 'A';
            $subsection->user_create = auth()->user()->id;
            $subsection->save();

            $this->actionElements($subsection->tags(), $data->tags, new Tag(), 'create');
            return $subsection;
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
                'section_id' => 'required'
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors);
                throw new \Exception($error);
            }

            $subsection = Subsection::find($id);
            if ($subsection == null) {
                throw new \Exception('No existe esta subsección');
            }
            $subsection->title = $data->title;
            $subsection->description = $data->description;
            $subsection->status = $data->status;
            $subsection->user_modifies = auth()->user()->id;
            $subsection->section_id = $data->section_id;

            $subsection->tags()->detach();
            $this->actionElements($subsection->tags(), $data->tags, new Tag(), 'update');

            $subsection->update();
            return $subsection;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($request, $id)
    {
        try {
            $subsection = Subsection::find($id);
            if ($subsection == null) {
                throw new \Exception('No existe esta subsección');
            }
            $subsection->status = 'E';
            $subsection->user_delete = auth()->user()->id;
            $subsection->date_delete = Carbon::now();
            $subsection->update($request->only('status', 'user_delete', 'date_delete'));
            return $subsection;
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
}