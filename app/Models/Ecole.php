<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ecole extends Model
{
     use HasFactory;

protected $fillable = ['name', 'address', 'phone', 'email'];

    public function stagiaires()
    {
        return $this->hasMany(Stagiaire::class);
    }

    

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
