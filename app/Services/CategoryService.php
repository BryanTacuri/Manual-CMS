<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryService
{
    public function getAll()
    {
        try {
            $categories = Category::all();
            if (count($categories) == 0) {
                throw new \Exception('No hay categorias');
            }
            if ((auth()->user())) {
                return Category::paginate();
            } else {
                return Category::where('status', 'A')->paginate(10);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create($data)
    {
        try {
            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:255',
                'user_create' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_decode($jsonErrors, TRUE);
                throw new \Exception($error);
            } else {
                $category = Category::create($data->all());
                return $category;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getId($id)
    {

        try {
            $category = Category::find($id);
            if ($category == null) {
                throw new \Exception('No existe esa categoria');
            }
            if ((auth()->user())) {
                return $category;
            } else {
                if ($category->status == 'A') {
                    return $category;
                } else {
                    throw new \Exception('La categoria no esta activa');
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
                'name' => 'required|string|max:255',
                'user_modifies' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                return json_decode($jsonErrors, TRUE);
            } else {
                $category = Category::findOrFail($id);
                if (!$category) {
                    throw new \Exception('No existe esa categoria');
                }
                $category->update($data->all());
                return $category;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($data, $id)
    {
        try {
            $validator = Validator::make($data->all(), [
                'status' => 'required|string|max:1',
                'user_delete' => 'required',
                'date_delete' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_decode($jsonErrors, TRUE);
                throw new \Exception($error);
            } else {
                $category = Category::findOrFail($id);
                if (!$category) {
                    throw new \Exception('No existe esa categoria');
                }
                $category->update($data->only('status', 'user_delete', 'date_delete'));
                return $category;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}