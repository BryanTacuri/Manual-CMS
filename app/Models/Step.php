<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    public function files()
    {
        return $this->morphToMany(File::class, 'filable')->withTimestamps();
    }

    //relacion uno a muchos (inversa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subsection()
    {
        return $this->belongsTo(Subsection::class);
    }
}