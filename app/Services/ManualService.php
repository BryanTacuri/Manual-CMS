<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Manual;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;

class ManualService
{
    private $elementManual;

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

    public function getCategoryManual($id)
    {
        try{
            $this->elementManual = "categories";
            $categories = $this->getElementsManual($id, $this->elementManual);
            return $categories;
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTagManual($id)
<<<<<<< Updated upstream
    {
        $manual = Manual::find($id);
        $tags = $manual->tags()->where('status', 'A')->get();
        return $tags;
    }

    public function create($data)
=======
>>>>>>> Stashed changes
    {
        try{
            $this->elementManual = "tags";
            $tags = $this->getElementsManual($id, $this->elementManual);
            return $tags;
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

<<<<<<< Updated upstream
                    if ($category != null) {
                        $manual->categories()->attach($category->id, ['user_create' => $data->user_create]);
                    }
                }

                $tagsNames = $data->tags;
                foreach ($tagsNames as $name) {
                    $tag = Tag::where('name', $name)->where('status', 'A')->first();

                    if ($tag != null) {
                        $manual->tags()->attach($tag->id, ['user_create' => $data->user_create]);
                    }
                }


                return $manual;
=======
    private function getElementsManual($id, $elementManual){
        try{
            $manual = Manual::find($id);
            if ($manual == null) {
                throw new \Exception('No existe este manual');
>>>>>>> Stashed changes
            }
            $element = $manual->$elementManual()->where('status', 'A')->get();
            return $element;
        }catch (\Exception $e) {
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
                $error = json_decode($jsonErrors, TRUE);
                throw new \Exception($error);
            }
            $manual = new Manual();
            $manual->title = $data->title;
            $manual->description = $data->description;
            $manual->status = $data->status;
            $manual->user_create = $data->user_create;

            $categorysNames = $data->categories;
            foreach ($categorysNames as $name) {
                $category = Category::where('name', $name)->where('status', 'A')->first();
                if ($category != null) {
                    $manual->categories()->attach($category->id, ['user_create' => $data->user_create]);
                }
            }

            $tagsNames = $data->tags;
            foreach ($tagsNames as $name) {
                $tag = Tag::where('name', $name)->where('status', 'A')->first();
                if ($tag != null) {
                    $manual->tags()->attach($tag->id, ['user_create' => $data->user_create]);
                }
            }
            $manual->save();
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
                $error =  json_decode($jsonErrors, TRUE);
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

            $categorysNames = $data->categories;
            $manual->categories()->detach();
            foreach ($categorysNames as $name) {
                $category = Category::where('name', $name)->where('status', 'A')->first();
                if ($category != null) {
                    $manual->categories()->attach($category->id, ['user_create' => $data->user_create, 'user_modifies' => $data->user_modifies]);
                }
            }

            $tagsNames = $data->tags;
            $manual->tags()->detach();
            foreach ($tagsNames as $name) {
                $tag = Tag::where('name', $name)->where('status', 'A')->first();
                if ($tag != null) {
                    $manual->tags()->attach($tag->id, ['user_create' => $data->user_create, 'user_modifies' => $data->user_modifies]);
                }
            }
            $manual->update();
            return $manual;
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
                $error =  json_decode($jsonErrors, TRUE);
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
}