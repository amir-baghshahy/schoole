<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'family', 'rolename', 'degree', 'teaching_experience', 'major', 'image', 'user_id', 'status'];

    public function scopeActive($query)
    {
        return $query->where('published', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}