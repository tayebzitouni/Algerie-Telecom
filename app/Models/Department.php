<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
     use HasFactory;

    protected $fillable = ['name','enterprise_id'];

  

    public function emplois()
    {
        return $this->hasMany(Emploi::class);
    }

 public function Groups()
    {
        return $this->hasMany(Group::class);
    }


}
