<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsection extends Model
{
    use HasFactory;
    public function categories()
    {
        return $this->morphToMany(Category::class, 'catable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    //relacion uno a muchos (inversa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    //Relación uno a muchos
    public function steps()
    {
        return $this->hasMany(Steps::class);
    }
}