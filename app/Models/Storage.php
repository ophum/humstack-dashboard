<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }

    public function machines()
    {
        return $this->belongsToMany('App\Models\Machine', 'attached_storages');
    }
}
