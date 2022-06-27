<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FileService
{
    public function getAll($data)
    {
        try {
            if (isset($data->embed)) {
                $embed = array_map('trim', explode(',', $data->embed));
                $files = File::with($embed)->get();
                return $files;
            }
            if (isset($data->search)) {
                $files = File::where('name', 'like', '%' . $data->search . '%')->get();
                return $files;
            }
            $files = File::all();
            if (count($files) == 0) {
                throw new \Exception('No hay archivos', 404);
            }
            if ((auth()->user())) {
                $files = File::latest('id')->paginate(10);
                return $files;
            }
            $files = File::where('status', 'A')->latest('id')->paginate(10);
            return $files;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getId($data, $id)
    {
        try {
            $file = File::find($id);
            if (isset($data->embed)) {
                $embed = array_map('trim', explode(',', $data->embed));
                $fileEmbed = File::with($embed)->get();
                return $fileEmbed;
            }
            if ($file == null) {
                throw new \Exception('No existe este archivo', 404);
            }
            if ((auth()->user())) {
                return $file;
            }
            if ($file->status != 'A') {
                throw new \Exception('El archivo no está activo', 404);
            }
            return $file;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:100',
                'path' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'size' => 'required|string|max:50',
                'extension' => 'required|string|max:50'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }
            $file = new File();
            $file->name = $data->name;
            $file->path = $data->path;
            $file->type = $data->type;
            $file->size = $data->size;
            $file->extension = $data->extension;
            $file->user_create = auth()->user()->id;
            $file->save();
            DB::commit();
            return $file;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data->all(), [
                'name' => 'required|string|max:100',
                'path' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'size' => 'required|string|max:50',
                'extension' => 'required|string|max:50',
                'status' => 'required|string|max:1'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }

            $file = File::find($id);
            if ($file == null) {
                throw new \Exception('No existe este archivo', 404);
            }
            $file->name = $data->name;
            $file->path = $data->path;
            $file->type = $data->type;
            $file->size = $data->size;
            $file->extension = $data->extension;
            $file->status = $data->status;
            $file->user_modifies = auth()->user()->id;
            $file->update();
            DB::commit();
            return $file;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $file = File::find($id);
            if ($file == null) {
                throw new \Exception('No existe este archivo', 404);
            }
            $file->status = 'E';
            $file->user_delete = auth()->user()->id;
            $file->date_delete = Carbon::now();
            $file->update();
            return $file;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}