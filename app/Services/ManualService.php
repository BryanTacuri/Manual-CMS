<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Manual;
use Illuminate\Support\Facades\Validator;

class ManualService
{
    public function getAll()
    {
        try{
            $manuals = Manual::all();
            if(count($manuals) == 0){
                throw new \Exception('No hay manuales');
            }
            if ((auth()->user())) {
                return Manual::latest('id')->paginate(10);
            } else {
                return Manual::where('status', 'A')->latest('id')->paginate(10);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function getCategoryManual($id){
        $manual = Manual::find($id);
        $categories = $manual->categories()->where('status', 'A')->get();
        return $categories;
    }

    public function create($data)
    {
        try{
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
            } else {
                $manual = new Manual();
                $manual->title = $data->title;
                $manual->description = $data->description;
                $manual->status = $data->status;
                $manual->user_create = $data->user_create;
                $manual->save();
    
                $categorysNames = $data->categorys;
                foreach ($categorysNames as $name) {
                    $category = Category::where('name', $name)->where('status', 'A')->first();
                    
                    if($category!=null){
                        $manual->categories()->attach($category->id, ['user_create'=>$data->user_create]);
                    }
                }
                return $manual;
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getId($id)
    {
        try{
            $manual = Manual::find($id);
            if($manual == null){
                throw new \Exception('No existe este manual');
            }
            if ((auth()->user())) {
                return $manual;
            } else {
                if($manual->status != 'A'){
                    throw new \Exception('El manual no está activo');
                }
                return $manual;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($data, $id)
    {
        try{
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
            } else {
                $manual = Manual::find($id);

                if($manual == null){
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
                    
                    if($category!=null){
                        $manual->categories()->attach($category->id, ['user_create'=>$data->user_create, 'user_modifies'=>$data->user_modifies]);
                    }
                }
                $manual->update();
                return $manual;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($request, $id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|max:1',
                'user_delete' => 'required',
                'date_delete' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_decode($jsonErrors, TRUE);
                throw new \Exception($error);
            } else {
                $manual = Manual::find($id);

                if($manual == null){
                    throw new \Exception('No existe este manual');
                }

                $manual->update($request->only('status', 'user_delete', 'date_delete'));
                return $manual;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}