<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }
}
