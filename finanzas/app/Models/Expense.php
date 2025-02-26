<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';
    protected $primaryKey = 'id_expense';

    protected $fillable = ['id_usuario', 'category', 'description', 'type', 'amount', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
