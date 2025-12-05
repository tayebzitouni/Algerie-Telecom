<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StagiaireStatusHistory extends Model
{
     use HasFactory;

    protected $table = 'stagiaire_status_history';

    protected $fillable = [
        'stagiaire_id',
        'status',
        'changed_at'
    ];

    protected $dates = ['changed_at'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class);
    }
}
