<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    public function problems()
    {
        return $this->hasMany('App\Models\Problem');
    }
}
