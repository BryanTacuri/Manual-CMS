<?php

namespace App\Services;

use App\Models\Subsection;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;

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
            } else {
                return Subsection::where('status', 'A')->latest('id')->paginate(10);
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
                'section_id' => 'required',
                'user_create' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);
                throw new \Exception($error);
            }
            $subsection = new Subsection();
            $subsection->title = $data->title;
            $subsection->description = $data->description;
            $subsection->status = $data->status;
            $subsection->section_id = $data->section_id;
            $subsection->user_create = $data->user_create;
            $subsection->save();

            $this->actionElements($subsection, $data, 'tags', new Tag(), 'create');
            return $subsection;
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
            } else {
                if ($subsection->status == 'A') {
                    return $subsection;
                } else {
                    throw new \Exception('No existe esa subsección');
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
                'title' => 'required|string|max:50',
                'description' => 'required|string|max:255',
                'status' => 'required|string|max:1',
                'section_id' => 'required',
                'user_modifies' => 'required',
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
            $subsection->user_create = $data->user_create;
            $subsection->user_modifies = $data->user_modifies;
            $subsection->section_id = $data->section_id;

            $subsection->tags()->detach();
            $this->actionElements($subsection, $data, 'tags', new Tag(), 'update');

            $subsection->update();
            return $subsection;
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
            $subsection = Subsection::find($id);
            if ($subsection == null) {
                throw new \Exception('No existe esta subsección');
            }
            $subsection->update($request->only('status', 'user_delete', 'date_delete'));
            return $subsection;
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