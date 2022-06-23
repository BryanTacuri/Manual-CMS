<?php

namespace App\Services;

use App\Models\Section;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Hamcrest\Core\HasToString;

class SectionService
{
    public function getAll()
    {
        try {
            $sections = Section::all();
            if (count($sections) == 0) {
                throw new \Exception('No hay secciones');
            }
            if ((auth()->user())) {
                return Section::paginate();
            } else {
                return Section::where('status', 'A')->paginate(10);
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
                'manual_id' => 'required',
                'user_create' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);

                throw new \Exception($error);
            }
            $section = new Section();
            $section->title = $data->title;
            $section->description = $data->description;
            $section->status = $data->status;
            $section->manual_id = $data->manual_id;
            $section->user_create = $data->user_create;
            $section->save();

            $this->actionElements($section, $data, 'categories', new Category(), 'create');

            $this->actionElements($section, $data, 'tags', new Tag(), 'create');
            return $section;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getId($id)
    {

        try {
            $section = Section::find($id);
            if ($section == null) {
                throw new \Exception('No existe esa sección');
            }
            if ((auth()->user())) {
                return $section;
            } else {
                if ($section->status == 'A') {
                    return $section;
                } else {
                    throw new \Exception('No existe esa sección');
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
                'user_modifies' => 'required',
                'manual_id' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors);

                throw new \Exception($error);
            }

            $section = Section::find($id);
            if ($section == null) {
                throw new \Exception('No existe este section');
            }
            $section->title = $data->title;
            $section->description = $data->description;
            $section->status = $data->status;
            $section->user_create = $data->user_create;
            $section->user_modifies = $data->user_modifies;
            $section->manual_id = $data->manual_id;


            $section->categories()->detach();
            $this->actionElements($section, $data, 'categories', new Category(), 'update');

            $section->tags()->detach();
            $this->actionElements($section, $data, 'tags', new Tag(), 'update');

            $section->update();
            return $section;
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
            $section = Section::find($id);
            if ($section == null) {
                throw new \Exception('No existe este section');
            }
            $section->update($request->only('status', 'user_delete', 'date_delete'));
            return $section;
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