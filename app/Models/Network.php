<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'require_gateway' => 'boolean',
    ];

    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }

    public function machines()
    {
        return $this->belongsToMany('App\Models\Machine', 'attached_nics');
    }
}
