<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
