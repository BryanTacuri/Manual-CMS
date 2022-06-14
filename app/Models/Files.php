<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;

    //relacion uno a muchos (inversa)
    public function step()
    {
        return $this->belongsTo(Steps::class);
    }
}