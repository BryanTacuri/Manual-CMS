<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class)->withTimestamps();
    }

    public function steps()
    {
        return $this->morphedByMany(Step::class, 'filable')->withTimestamps();
    }
}