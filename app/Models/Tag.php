<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    //relacion muchos a muchos inversa 
    public function manuals()
    {
        return $this->morphedByMany(Manual::class, 'taggable')->withTimestamps();
    }

    public function section()
    {
        return $this->morphedByMany(Section::class, 'taggable')->withTimestamps();
    }

    public function subsection()
    {
        return $this->morphedByMany(Subsection::class, 'taggable')->withTimestamps();
    }

    public function steps()
    {
        return $this->morphedByMany(Steps::class, 'taggable')->withTimestamps();
    }
}