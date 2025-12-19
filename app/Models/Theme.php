<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'duration',
        'description',
        'requirements',
        'learning_objectives',
        'max_capacity',
        'difficulty_level',
        'documentation_path',
        'employee_id', // added
    ];

    // Relations
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function employee()
    {
        return $this->belongsTo(Emploi::class, 'employee_id');
    }
}
