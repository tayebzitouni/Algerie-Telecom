<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stagiaire extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'group_id',
        'status'
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

    
    public function statusHistory()
    {
        return $this->hasMany(StagiaireStatusHistory::class, 'stagiaire_id');
    }

   

}
