<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['id_user', 'category', 'description', 'type', 'amount', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

}
