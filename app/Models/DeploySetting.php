<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DeploySetting extends Pivot
{
    use HasFactory;

    protected $table = 'deploy_settings';

    protected $guarded = [];

    public function node()
    {
        return $this->belongsTo('App\Models\Node');
    }

    public function problem()
    {
        return $this->belongsTo('App\Models\Problem');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
