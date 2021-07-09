<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time', 'cause', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}