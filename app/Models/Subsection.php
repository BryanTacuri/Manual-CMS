<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsection extends Model
{
    use HasFactory;

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    //relacion uno a muchos (inversa)
    public function user()
    {
        return $this->belongsTo(User::class)->withTimestamps();
    }

    public function section()
    {
        return $this->belongsTo(Section::class)->withTimestamps();
    }

    //Relación uno a muchos
    public function steps()
    {
        return $this->hasMany(Steps::class)->withTimestamps();
    }
}