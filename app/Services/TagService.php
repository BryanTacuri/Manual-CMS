<?php

namespace App\Services;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TagService
{
    public function getAll($data)
    {
        try {
            if (isset($data->embed)) {
                $embed = array_map('trim', explode(',', $data->embed));
                $tags = Tag::with($embed)->get();
                return $tags;
            }
            if (isset($data->search)) {
                $tags = Tag::where('name', 'like', '%' . $data->search . '%')->get();
                return $tags;
            }
            $tags = Tag::all();
            if (count($tags) == 0) {
                throw new \Exception('No hay tags', 404);
            }
            if ((auth()->user())) {
                $tags = Tag::latest('id')->paginate(10);
            }
            $tags = Tag::where('status', 'A')->latest('id')->paginate(10);
            return $tags;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }
            $tag = new Tag();
            $tag->name = $data->name;
            $tag->user_create = auth()->user()->id;
            $tag->save();
            DB::commit();
            return $tag;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getId($data, $id)
    {
        try {
            $tag = Tag::find($id);
            if (isset($data->embed)) {
                $embed = array_map('trim', explode(',', $data->embed));
                $tagEmbed = $tag->with($embed)->get();
                return $tagEmbed;
            }
            if ($tag == null) {
                throw new \Exception('No existe esa tag', 404);
            }
            if ((auth()->user())) {
                return $tag;
            }
            if ($tag->status != 'A') {
                throw new \Exception('La tag no esta activa', 404);
            } 
            return $tag;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|string|max:1'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }
            $tag = Tag::find($id);
            if ($tag == null) {
                throw new \Exception('No existe esa tag', 404);
            }
            $tag->name = $data->name;
            $tag->status = $data->status;
            $tag->user_modifies = auth()->user()->id;
            $tag->update();
            DB::commit();
            return $tag;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $tag = Tag::find($id);
            if (!$tag) {
                throw new \Exception('No existe esa tag', 404);
            }
            $tag->status = 'E';
            $tag->user_delete = auth()->user()->id;
            $tag->date_delete = Carbon::now();
            $tag->update();
            return $tag;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}