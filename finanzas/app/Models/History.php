<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'histories';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['id_user', 'total_entries', 'total_expenses', 'month', 'year'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
