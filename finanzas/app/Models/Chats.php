<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    use HasFactory;

    protected $table = 'chat';
    protected $primaryKey = 'id_message';

    protected $fillable = ['id_user', 'sender', 'message', 'timestamp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
