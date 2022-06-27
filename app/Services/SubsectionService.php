<?php

namespace App\Services;

use App\Models\Subsection;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubsectionService
{
    public function getAll($data)
    {
        try {
            if (isset($data->embed)) {
                $embed = array_map('trim', explode(',', $data->embed));
                foreach ($embed as $value) {
                    if ($value == 'manual') {
                        $subsections['manual'] = Subsection::with('section.manual')->get();
                    } else {
                        $subsections[$value] = Subsection::with($value)->get();
                    }
                }
                return $subsections;
            }
            if (isset($data->search)) {
                $subsections = Subsection::with('tags')->whereHas('tags', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data->search . '%');
                })->orWhere('title', 'like', '%' . $data->search . '%')->get();

                return $subsections;
            }
            $subsections = Subsection::all();
            if (count($subsections) == 0) {
                throw new \Exception('No hay subsecciones', 404);
            }
            if ((auth()->user())) {
                $subsections = Subsection::latest('id')->with('tags')->paginate(10);
                return $subsections;
            }
            $subsections = Subsection::where('status', 'A')->latest('id')->with('tags')->paginate(10);
            return $subsections;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getId($data, $id)
    {
        try {
            $subsection = Subsection::find($id);
            if (isset($data->embed)) {
                $embed = array_map('trim', explode(',', $data->embed));
                foreach ($embed as $value) {
                    if ($value == 'manual') {
                        $subsectionsEmbed['manual'] = Subsection::with('section.manual')->get();
                    } else {
                        $subsectionsEmbed[$value] = Subsection::with($value)->get();
                    }
                }
                return $subsectionsEmbed;
            }
            if ($subsection == null) {
                throw new \Exception('No existe esta subsección', 404);
            }
            if ((auth()->user())) {
                return $subsection;
            }
            if ($subsection->status != 'A') {
                throw new \Exception('La subsección no está activo', 404);
            }
            return $subsection;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'section_id' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }
            $subsection = new Subsection();
            $subsection->title = $data->title;
            $subsection->description = $data->description;
            $subsection->section_id = $data->section_id;
            $subsection->user_create = auth()->user()->id;
            $subsection->save();

            $this->actionElements($subsection->tags(), $data->tags, new Tag(), 'create');
            $subsection->tags;
            DB::commit();
            return $subsection;
        } catch (\Exception $e) {
            throw $e;
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
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }

            $subsection = Subsection::find($id);
            if ($subsection == null) {
                throw new \Exception('No existe esta subsección', 404);
            }
            $subsection->title = $data->title;
            $subsection->description = $data->description;
            $subsection->status = $data->status;
            $subsection->user_modifies = auth()->user()->id;
            $subsection->section_id = $data->section_id;

            $subsection->tags()->detach();
            $this->actionElements($subsection->tags(), $data->tags, new Tag(), 'update');

            $subsection->update();
            $subsection->tags;
            DB::commit();
            return $subsection;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($request, $id)
    {
        try {
            $subsection = Subsection::find($id);
            if ($subsection == null) {
                throw new \Exception('No existe esta subsección', 404);
            }
            $subsection->status = 'E';
            $subsection->user_delete = auth()->user()->id;
            $subsection->date_delete = Carbon::now();
            $subsection->update($request->only('status', 'user_delete', 'date_delete'));
            return $subsection;
        } catch (\Exception $e) {
            throw $e;
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
            throw $e;
        }
    }
}