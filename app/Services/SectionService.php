<?php

namespace App\Services;

use App\Models\Section;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Carbon\Carbon;

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
                'manual_id' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);

                throw new \Exception($error);
            }
            $section = new Section();
            $section->title = $data->title;
            $section->description = $data->description;
            $section->manual_id = $data->manual_id;
            $section->user_create = auth()->user()->id;
            $section->save();

            $this->actionElements($section->categories(), $data->categories, new Category(), 'create');
            $this->actionElements($section->tags(), $data->tags, new Tag(), 'create');

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
            }
            if ($section->status != 'A') {
                throw new \Exception('La subsección no está activo');
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
            $section->manual_id = $data->manual_id;
            $section->user_modifies = auth()->user()->id;

            $section->categories()->detach();
            $this->actionElements($section->categories(), $data->categories, new Category(), 'update');

            $section->tags()->detach();
            $this->actionElements($section->tags(), $data->tags, new Tag(), 'update');

            $section->update();
            return $section;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $section = Section::find($id);
            if ($section == null) {
                throw new \Exception('No existe este section');
            }
            $section->status = 'E';
            $section->user_delete = auth()->user()->id;
            $section->date_delete = Carbon::now();
            $section->update();
            return $section;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function actionElements($subsection, $data, $model, $action)
    {
        try {
            foreach ($data as $name) {
                $element = $model::where('name', $name)->where('status', 'A')->first();
                if ($element != null && $action == 'create') {
                    $subsection->attach($element->id, ['user_create' => auth()->user()->id]);
                }
                if ($element != null && $action == 'update') {
                    $subsection->attach($element->id, ['user_create' => auth()->user()->id, 'user_modifies' => auth()->user()->id]);
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}