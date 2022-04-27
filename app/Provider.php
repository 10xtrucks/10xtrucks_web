<?php

namespace App;

use App\Notifications\ProviderResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Provider extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'mobile',
        'address',
        'picture',
        'gender',
        'latitude',
        'longitude',
        'status',
        'avatar',
        'social_unique_id',
        'fleet',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at'
    ];

    protected $appends = [
        'total_requests','accepted_requests','cancelled_requests'
    ];

    /**
     * The services that belong to the user.
     */
    public function service()
    {
        return $this->hasOne('App\ProviderService');
    }

    /**
     * The services that belong to the user.
     */
    public function incoming_requests()
    {
        return $this->hasMany('App\RequestFilter')->where('status', 0);
    }

    /**
     * The services that belong to the user.
     */
    public function requests()
    {
        return $this->hasMany('App\RequestFilter');
    }

    /**
     * The services that belong to the user.
     */
    public function profile()
    {
        return $this->hasOne('App\ProviderProfile');
    }

    /**
     * The services that belong to the user.
     */
    public function device()
    {
        return $this->hasOne('App\ProviderDevice');
    }

    /**
     * The services that belong to the user.
     */
    public function trips()
    {
        return $this->hasMany('App\UserRequests');
    }



    public function active_documents()
    {
        return $this->hasMany('App\ProviderDocument')->where('status', 'ACTIVE')->count();
    }  

    /**
     * The services accepted by the provider
     */
    public function accepted()
    {
        return $this->hasMany('App\UserRequests','provider_id')
                    ->where('status','!=','CANCELLED');
    }

    /**
     * service cancelled by provider.
     */
    public function cancelled()
    {
        return $this->hasMany('App\UserRequests','provider_id')
                ->where('status','CANCELLED');
    }

    /**
     * The services that belong to the user.
     */
    public function documents()
    {
        return $this->hasMany('App\ProviderDocument');
    }

    /**
     * The services that belong to the user.
     */
    public function document($id)
    {
        return $this->hasOne('App\ProviderDocument')->where('document_id', $id)->first();
    }

    /**
     * The services that belong to the user.
     */
    public function pending_documents()
    {
        return $this->hasMany('App\ProviderDocument')->where('status', 'ASSESSING')->count();
    }

    public function pending_document()
    {
        return $this->hasMany('App\ProviderDocument')->where('status', 'ASSESSING');
    }


   /**
     * The provider bidding current reqeust.
     */
    public function providerbidding()
    {
        return $this->hasOne('App\ProviderBidding');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ProviderResetPassword($token));
    }

    public function getTotalRequestsAttribute(){
        $count = UserRequests::where('provider_id',$this->attributes['id'])->count();
        return $count;
    }

    public function getAcceptedRequestsAttribute(){
        $count = UserRequests::where('provider_id',$this->attributes['id'])->where('status','ACCEPTED')->count();
        return $count;
    }

    public function getCancelledRequestsAttribute(){
        $count = UserRequests::where('provider_id',$this->attributes['id'])->where('status','CANCELLED')->count();
        return $count;
    }
}
