<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //relacion uno a muchos (inversa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relacion muchos a muchos inversa 
    public function manuals()
    {
        return $this->morphedByMany(Manual::class, 'catable');
    }

    public function section()
    {
        return $this->morphedByMany(Section::class, 'catable');
    }

    public function subsection()
    {
        return $this->morphedByMany(Subsection::class, 'catable');
    }

    public function steps()
    {
        return $this->morphedByMany(Steps::class, 'catable');
    }
}