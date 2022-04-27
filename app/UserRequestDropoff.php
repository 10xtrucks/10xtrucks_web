<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRequestDropoff extends Model
{

    protected $table = 'user_request_dropoff';
     /**
     * The attributes that are mass assignable.
     *
     * @var array



     */

     protected $fillable = [
        'user_request_id',
        'status',
        's_address',
        's_latitude',
        's_longitude',
        'd_latitude',       
        'd_longitude',
        'd_address',
        'service_items',
        'user_rated',
        'provider_rated',
        'after_image',
        'otp'
    ];


     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * ServiceType Model Linked
     */
    public function user_request()
    {
        return $this->belongsTo('App\UserRequests','id');
    }


}
