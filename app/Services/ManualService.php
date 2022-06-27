<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Manual;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManualService
{
    public function getAll($data)
    {
        try {
            if (isset($data->embed)) {
                $embed = array_map('trim', explode(',', $data->embed));
                foreach ($embed as $value) {
                    if ($value == 'subsections') {
                        $manual['subsections'] = Manual::with('sections.subsections')->get();
                    } else {
                        $manual[$value] = Manual::with($value)->get();
                    }
                }
                return $manual;
            }
            if (isset($data->search)) {

                $manual = Manual::with('categories', 'tags')->whereHas('tags', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data->search . '%');
                })->orWhere('title', 'like', '%' . $data->search . '%')->get();

                return $manual;
            }
            $manuals = Manual::all();
            if (count($manuals) == 0) {
                throw new \Exception('No hay manuales', 404);
            }
            if ((auth()->user())) {
                $manual =  Manual::latest('id')->with('categories', 'tags')->paginate(10);
                return $manual;
            }
            $manual =  Manual::where('status', 'A')->latest('id')->with('categories', 'tags')->paginate(10);
            return $manual;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getId($data, $id)
    {
        try {
            $manual = Manual::find($id);
            if (isset($data->embed)) {
                $embed = array_map('trim', explode(',', $data->embed));
                foreach ($embed as $value) {
                    if ($value == 'subsections') {
                        $manualEmbed['subsections'] = $manual->with('sections.subsections')->get();
                    } else {
                        $manualEmbed[$value] = $manual->with($value)->get();
                    }
                }
                return $manualEmbed;
            }
            if ($manual == null) {
                throw new \Exception('No existe este manual', 404);
            }
            if ((auth()->user())) {
                return $manual;
            }
            if ($manual->status != 'A') {
                throw new \Exception('El manual no está activo', 404);
            }
            return $manual;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data->all(), [
                'title' => 'required|string|max:50',
                'description' => 'required|string|max:255'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }

            $manual = new Manual();
            $manual->title = $data->title;
            $manual->description = $data->description;
            $manual->user_create = auth()->user()->id;
            $manual->save();


            $this->actionElements($manual->categories(), $data->categories, new Category(), 'create');
            $this->actionElements($manual->tags(), $data->tags, new Tag(), 'create');

            $manual->categories;
            $manual->tags;
            DB::commit();
            return  $manual;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data->all(), [
                'title' => 'required|string|max:50',
                'description' => 'required|string|max:255',
                'status' => 'required|string|max:1'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }
            $manual = Manual::find($id);
            if ($manual == null) {
                throw new \Exception('No existe este manual', 404);
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
            $manual->categories;
            $manual->tags;
            DB::commit();

            return $manual;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $manual = Manual::find($id);
            if ($manual == null) {
                throw new \Exception('No existe este manual', 404);
            }
            $manual->status = 'E';
            $manual->user_delete = auth()->user()->id;
            $manual->date_delete = Carbon::now();
            $manual->update();
            return $manual;
        } catch (\Exception $e) {
            throw $e;
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
            throw $e;
        }
    }
}