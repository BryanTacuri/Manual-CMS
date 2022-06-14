<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
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

    public function manual()
    {
        return $this->belongsTo(Manual::class);
    }

    //Relación uno a muchos
    public function subsections()
    {
        return $this->hasMany(Subsection::class);
    }
}