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
        if ((auth()->user())) {
            return Category::paginate();
        } else {
            return Category::where('status', 'A')->paginate(10);
        }
    }

    public function create($data)
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'user_create' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $category = Category::create($data->all());
            return $category;
        }
    }

    public function getId($id)
    {
        $category = Category::find($id);
        return $category;
    }
    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_modifies' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $category = Category::findOrFail($id);
            $category->update($request->all());
            return $category;
        }
    }

    public function delete($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|max:1',
            'user_delete' => 'required',
            'date_delete' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $category = Category::findOrFail($id);
            $category->update($request->only('status', 'user_delete', 'date_delete'));
            return $category;
        }
    }
}