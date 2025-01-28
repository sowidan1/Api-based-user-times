<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    protected $table = "durations";

    protected $fillable = [
        'time_unit',
        'time_value',
        'start_time',
        'end_time',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    
}
