<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    public function deployedProblems()
    {
        return $this
            ->belongsToMany('App\Models\Problem', 'deploy_settings')
            ->using('App\Models\DeploySetting')
            ->withPivot(['node_id', 'status', 'storage_type']);
    }
}
