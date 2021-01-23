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

    public function machines()
    {
        return $this->hasMany('App\Models\Machine');
    }

    public function storages()
    {
        return $this->hasMany('App\Models\Storage');
    }

    public function networks()
    {
        return $this->hasMany('App\Models\Network');
    }

    public function deployedTeams()
    {
        return $this
            ->belongsToMany('App\Models\Team', 'deploy_settings')
            ->using('App\Models\DeploySetting')
            ->withPivot(['node_id', 'status', 'storage_type']);
    }
}
