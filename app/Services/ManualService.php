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
        if ((auth()->user())) {
            return Manual::latest('id')->paginate(10);
        } else {
            return Manual::where('status', 'A')->latest('id')->paginate(10);
        }
        //retornar las tagg de ese 
    }

    public function create($data)
    {
        $validator = Validator::make($data->all(), [
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'status' => 'required|string|max:1',
            'user_create' => 'required'
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
                
                if($category!=null){
                    $manual->categories()->attach($category->id, ['user_create'=>$data->user_create]);
                }else{
                    break;
                }
            }
            return $manual;
        }
    }

    public function getId($id)
    {
        if ((auth()->user())) {
            return Manual::find($id);
        } else {
            return Manual::where('id', $id)->where('status', 'A')->get();
        }
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'status' => 'required|string|max:1',
            'user_modifies' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $manual = Manual::findOrFail($id);
            $manual->title = $request->title;
            $manual->description = $request->description;
            $manual->status = $request->status;
            $manual->user_create = $request->user_create;
            $manual->user_modifies = $request->user_modifies;

            $categorysNames = $request->categorys;

            $manual->categories()->detach();
            foreach ($categorysNames as $name) {
                $category = Category::where('name', $name)->first();
                $manual->categories()->attach($category->id, ['user_create'=>$request->user_create,'user_modifies'=>$request->user_modifies]);
            }
            $manual->update();
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