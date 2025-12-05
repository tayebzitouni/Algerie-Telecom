<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class GroupProgress extends Model
{

    use HasFactory;

    protected $table = 'group_progress';

    protected $fillable = [
        'group_id',
        'note',
        'date'
    ];

    protected $dates = ['date'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

   
    //
}
