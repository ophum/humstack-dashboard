<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function deploySettings()
    {
        return $this->hasMany('App\Models\DeploySetting');
    }
}
