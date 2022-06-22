<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Facades\Validator;

class TagService
{
    public function getAll()
    {
        try {
            $tags = Tag::all();
            if (count($tags) == 0) {
                throw new \Exception('No hay tags');
            }
            if ((auth()->user())) {
                return Tag::paginate();
            } else {
                return Tag::where('status', 'A')->paginate(10);
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
                $tag = Tag::create($data->all());
                return $tag;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getId($id)
    {

        try {
            $tag = Tag::find($id);
            if ($tag == null) {
                throw new \Exception('No existe esa tag');
            }
            if ((auth()->user())) {
                return $tag;
            } else {
                if ($tag->status == 'A') {
                    return $tag;
                } else {
                    throw new \Exception('La tag no esta activa');
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
                'user_update' => 'required',
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_decode($jsonErrors, TRUE);
                throw new \Exception($error);
            } else {
                $tag = Tag::find($id);
                if ($tag == null) {
                    throw new \Exception('No existe esa tag');
                }
                $tag->update($data->all());
                return $tag;
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
                $tag = Tag::find($id);
                if (!$tag) {
                    throw new \Exception('No existe esa tag');
                }
                $tag->update($data->only('status', 'user_delete', 'date_delete'));
                return $tag;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}