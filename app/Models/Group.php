<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'program', 'theme_id', 'ecole_id', 'emploi_id'];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function stagiaires()
    {
        return $this->hasMany(Stagiaire::class);
    }

    public function progress()
    {
        return $this->hasMany(GroupProgress::class);
    }

    public function emploi()
    {
        return $this->belongsTo(Emploi::class);
    }
}
