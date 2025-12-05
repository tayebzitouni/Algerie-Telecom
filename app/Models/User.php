<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
  use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role' // hr or emploi
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * public function emploi()
{
    return $this->hasOne(Emploi::class);
}

     * 
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

public function emploi()
{
    return $this->hasOne(Emploi::class);
}

    
  
}
