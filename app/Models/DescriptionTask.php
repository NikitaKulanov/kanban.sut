<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescriptionTask extends Model
{
    use HasFactory;

    /**
     * Get jobs to which the description applies.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function userAuthor()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
