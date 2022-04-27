<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderBidding extends Model
{
   	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_request_id','provider_id','bidding_amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'created_at', 'updated_at'
    ];

    /**
     * The services that belong to the user.
     */
    public function request()
    {
        return $this->belongsTo('App\UserRequests');
    }

   /**
     * The provider bidding current reqeust.
     */
    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }
}
