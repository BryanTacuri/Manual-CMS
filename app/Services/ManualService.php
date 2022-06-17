<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Manual;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;

class ManualService
{
    public function getAll()
    {
        //VALIDAR SI STATUS I O E NO MOSTRAR
        $manual = Manual::all();
        return $manual;
    }

    public function create($data)
    {
        $validator = Validator::make($data->all(), [
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'status' => 'required',
            'user_create' => 'required',
            'categorys'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $manual = new Manual();
            $manual->title = $data->title;
            $manual->description = $data->description;
            $manual->status = $data->status;
            $manual->user_create = $data->user_create;
            $manual->save();

            $categorysNames = $data->categorys;

            foreach ($categorysNames as $name) {
                $category = Category::where('name', $name)->first();
                $manual->categories()->attach($category->id);
            }
            return $manual;
        }
    }

    // public function getById($id)
    // {
    //     //VALIDARR SI STATUS I O E NO MOSTRAR
    //     return Manual::find($id);
    // }

    public function getId($id)
    {
        $manual = Manual::find($id);
        return $manual;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'status' => 'required',
            'user_modifies' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            /*  $manual = Manual::findOrFail($id);
            $manual->update($request->all());
            return $manual; */
            $manual = Manual::findOrFail($id);
            $manual->title = $request->title;
            $manual->description = $request->description;
            $manual->status = $request->status;
            $manual->user_create = $request->user_create;

            $categorysNames = $request->categorys;

            $manual->categories()->detach();
            foreach ($categorysNames as $name) {
                $category = Category::where('name', $name)->first();
                $manual->categories()->attach($category->id);
            }
            return $manual;
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
            $manual = Manual::findOrFail($id);
            $manual->update($request->only('status', 'user_delete', 'date_delete'));
            return $manual;
        }
    }
}