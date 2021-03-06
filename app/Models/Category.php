<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relacion uno a muchos (inversa)
    public function user()
    {
        return $this->belongsTo(User::class)->withTimestamps();
    }

    //relacion muchos a muchos inversa 
    public function manuals()
    {
        return $this->morphedByMany(Manual::class, 'catable')->withTimestamps()->withPivot('user_create');
    }

    public function section()
    {
        return $this->morphedByMany(Section::class, 'catable')->withTimestamps();
    }
}