<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'role',
        'status',
        'status_cause',
        'password',
        'archive',
        'super_user'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'super_user'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['archive' => 'boolean', 'super_user' => 'boolean'];

    protected $guarded  = ['super_user'];


    public function setPasswordAttribute($value)
    {
        return  $this->attributes['password'] =  Hash::make($value);
    }



    public function account()
    {
        return $this->belongsTo(Account::class, 'id', 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'id', 'user_id');
    }

    public function disciplines()
    {
        return $this->hasMany(Discipline::class, 'id', 'user_id');
    }

    public function scopeArchive($query, $arg = false)
    {
        return $query->where('archive', $arg);
    }
}
