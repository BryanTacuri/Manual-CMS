<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FileService
{
    public function getAll()
    {
        try {
            $files = File::all();
            if (count($files) == 0) {
                throw new \Exception('No hay archivos');
            }
            if ((auth()->user())) {
                return File::latest('id')->paginate(10);
            }
            return File::where('status', 'A')->latest('id')->paginate(10);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getId($id)
    {
        try {
            $file = File::find($id);
            if ($file == null) {
                throw new \Exception('No existe este archivo');
            }
            if ((auth()->user())) {
                return $file;
            }
            if ($file->status != 'A') {
                throw new \Exception('El archivo no está activo');
            }
            return $file;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create($data)
    {
        try {
            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:100',
                'path' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'size' => 'required|string|max:50',
                'extension' => 'required|string|max:50'
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors, TRUE);
                throw new \Exception($error);
            }
            $file = new File();
            $file->name = $data->name;
            $file->path = $data->path;
            $file->type = $data->type;
            $file->size = $data->size;
            $file->extension = $data->extension;
            $file->user_create = auth()->user()->id;
            $file->save();
            return $file;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($data, $id)
    {
        try {
            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:100',
                'path' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'size' => 'required|string|max:50',
                'extension' => 'required|string|max:50'
            ]);
            if ($validator->fails()) {
                $jsonErrors =  $validator->errors();
                $error =  json_encode($jsonErrors);
                throw new \Exception($error);
            }

            $file = File::find($id);
            if ($file == null) {
                throw new \Exception('No existe este archivo');
            }
            $file->name = $data->name;
            $file->path = $data->path;
            $file->type = $data->type;
            $file->size = $data->size;
            $file->extension = $data->extension;
            $file->status = $data->status;
            $file->user_modifies = auth()->user()->id;
            $file->update();
            return $file;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($request, $id)
    {
        try {
            $file = File::find($id);
            if ($file == null) {
                throw new \Exception('No existe este archivo');
            }
            $file->status = 'E';
            $file->user_delete = auth()->user()->id;
            $file->date_delete = Carbon::now();
            $file->update($request->only('status', 'user_delete', 'date_delete'));
            return $file;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}