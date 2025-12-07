<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stagiaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'city',
        'group_id',
        'status',
        'cv_path',
        'student_card_path',
        'cover_letter_path'
    ];

    protected static function booted()
    {
        static::created(function (Stagiaire $stagiaire) {
            $stagiaire->statusHistory()->create([
                'status' => $stagiaire->status,
                'changed_at' => now()
            ]);
        });

        static::updating(function (Stagiaire $stagiaire) {
            if ($stagiaire->isDirty('status')) {
                $stagiaire->statusHistory()->create([
                    'status' => $stagiaire->status,
                    'changed_at' => now()
                ]);
            }
        });
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

 public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }


    public function emploi()
    {
        return $this->belongsTo(Emploi::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(StagiaireStatusHistory::class, 'stagiaire_id');
    }
}
