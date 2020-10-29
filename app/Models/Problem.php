<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
}
