<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $table = 'users';

    protected $fillable = ['name', 'age', 'username', 'password', 'email'];

    public function entries()
    {
        return $this->hasMany(Entrie::class, 'id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'id');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'id');
    }
}
