<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'national_code', 'family', 'major_name', 'birthday', 'grade', 'address', 'dad_name', 'dad_phone', 'dad_work_address', 'dad_is_dead', 'mom_phone', 'mom_work_address', 'mom_is_dead', 'relatives_phone', 'relatives_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}