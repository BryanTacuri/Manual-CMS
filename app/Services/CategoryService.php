<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use PhpParser\Node\Stmt\TryCatch;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class CategoryService
{
    public function getAll()
    {
        try {
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
                return $error;
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
            if ((auth()->user())) {
                return Category::find($id);
            } else {
                return Category::where('id', $id)->where('status', 'A')->get();
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
                return json_decode($jsonErrors, TRUE);
            } else {
                $category = Category::findOrFail($id);
                $category->update($data->only('status', 'user_delete', 'date_delete'));
                return $category;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}