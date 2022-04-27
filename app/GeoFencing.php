<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeoFencing extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    protected $fillable = [
        'ranges'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
