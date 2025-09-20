<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $fillable = [
        'name',
        'winning_number',
        'played_at'
    ];

    protected $casts = [
        'played_at' => 'datetime'
    ];
}
