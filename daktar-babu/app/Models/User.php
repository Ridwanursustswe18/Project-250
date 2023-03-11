<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'doc_id');
    }

    //same go to user details
    public function user_details()
    {
        return $this->hasOne(UserDetails::class, 'user_id');
    }

    //a user may has many appointments
    // public function appointments()
    // {
    //     return $this->hasMany(Appointments::class, 'user_id');
    // }

    // //a user may has many reviews
    // public function reviews()
    // {
    //     return $this->hasMany(Reviews::class, 'user_id');
    // }
}
