<?php

namespace App\Models;

use App\Contracts\BindingModelContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model implements BindingModelContract
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s';

    public function getSenderId(): int
    {
        return $this->sender_id;
    }

    public function userSender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
