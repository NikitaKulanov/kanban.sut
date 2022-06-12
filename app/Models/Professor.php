<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Professor extends Model
{
    use HasFactory;

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(Discipline::class);
    }
}
