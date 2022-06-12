<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileTask extends Model
{
    use HasFactory;

    protected $table = 'task_file';
    protected $guarded = false;
    public $timestamps = false;
}
