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

    public function attachedNics()
    {
        return $this->belongsToMany('App\Models\Network', 'attached_nics')
            ->withPivot('ipv4_address', 'default_gateway', 'nameserver', 'order')
            ->orderBy('pivot_order', 'asc');
    }

    public function attachedStorages()
    {
        return $this->belongsToMany('App\Models\Storage', 'attached_storages')
            ->withPivot('order')
            ->orderBy('pivot_order', 'asc');
    }
}
