<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Group extends Model
{
    use HasFactory;

    public function students(): Relations\HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function disciplines(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Discipline::class);
    }
}
