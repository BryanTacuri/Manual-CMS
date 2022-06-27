<?php

namespace App\Services;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CategoryService
{
    //1. Borrar tanta cosas en validations
    //3. Aagregar status code en el excepcion
    //4. En el catch retornar el throw.
    public function getAll($data)
    {
        try {
            if (isset($data->search)) {
                $categories = Category::where('name', 'like', '%' . $data->search . '%')->get();
                return $categories;
            }
            $categories = Category::all();
            if (count($categories) == 0) {
                throw new \Exception('Categorias no encontradas', 404);
            }
            if ((auth()->user())) {
                return  Category::latest('id')->paginate(10);
            } else {
                return Category::where('status', 'A')->latest('id')->paginate(10);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create($data)
    {
        try {
            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                $error =  $validator->errors()->first();
                throw new \Exception($error, 400);
            } else {
                $category = new Category();
                $category->name = $data->name;
                $category->user_create = auth()->user()->id;
                $category->save();
                return $category;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getId($id)
    {
        try {

            $category = Category::find($id);
            if ($category == null) {
                throw new \Exception('No existe esa categoria', 404);
            }
            if ((auth()->user())) {
                return $category;
            } else {
                if ($category->status == 'A') {
                    return $category;
                } else {
                    throw new \Exception('La categoria no esta activa', 404);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function update($data, $id)
    {
        try {

            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|string|max:1'
            ]);
            if ($validator->fails()) {
                $error =  $validator->errors()->first();
                throw new \Exception($error);
            }
            $category = Category::find($id);
            if (!$category) {
                throw new \Exception('No existe esa categoria', 404);
            }
            $category->name = $data->name;
            $category->status = $data->status;
            $category->user_modifies = auth()->user()->id;
            $category->update();
            return $category;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                throw new \Exception('No existe esa categoria', 404);
            }
            $category->status = 'E';
            $category->user_delete = auth()->user()->id;
            $category->date_delete = Carbon::now();
            $category->update();
            return $category;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}