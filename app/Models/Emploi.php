<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Emploi extends Model
{
     use HasFactory;
    protected $fillable = [ 'department_id', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function stagiaires()
    {
        return $this->hasMany(Stagiaire::class);
    }
}
