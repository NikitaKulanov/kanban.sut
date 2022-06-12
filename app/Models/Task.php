<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * Get task description.
     */
    public function descriptionTask()
    {
        return $this->belongsTo(DescriptionTask::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function userPerpetrator()
    {
        return $this->belongsTo(User::class, 'perpetrator_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'task_file', 'task_id', 'file_id');
    }

    public function links()
    {
        return $this->belongsToMany(Link::class, 'task_link', 'task_id', 'link_id');
    }
}
