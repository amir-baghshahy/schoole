<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'family', 'rolename', 'degree', 'teaching_experience', 'major', 'image', 'user_id', 'status', 'birthday', 'shabanumber'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}