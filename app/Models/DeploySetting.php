<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DeploySetting extends Pivot
{
    use HasFactory;

    protected $guarded = [];

    public function node()
    {
        return $this->belongsTo('App\Models\Node');
    }
}
