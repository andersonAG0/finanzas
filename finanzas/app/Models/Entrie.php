<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrie extends Model
{
    use HasFactory;

    protected $table = 'entries';
    protected $primaryKey = 'id_entrie';

    protected $fillable = ['id_user', 'source', 'description', 'amount', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
