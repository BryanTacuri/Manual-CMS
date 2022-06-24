<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relacion muchos a muchos (copar y pegar en subseccion, section, pasos,)
    public function categories()
    {
        return $this->morphToMany(Category::class, 'catable')->withTimestamps();
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    //relacion uno a muchos (inversa)
    public function user()
    {
        return $this->belongsTo(User::class)->withTimestamps();;
    }

    //Relación uno a muchos
    public function sections()
    {
        return $this->hasMany(Section::class)->withTimestamps();;
    }
}