<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ProviderDevice;
use Exception;
use Setting;
use Edujugon\PushNotification\PushNotification;

class SendPushNotification extends Controller
{
	/**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function RideAccepted($request){

        //\Log::info("Ride Accepted--------" .$request);

    	return $this->sendPushToUser($request->user_id, trans('api.push.request_accepted'));
    }

    /**
     * Driver Arrived at your location.
     *
     * @return void
     */
    public function user_schedule($user){

        //\Log::info("user_schedule--------" .$user);

        return $this->sendPushToUser($user, trans('api.push.schedule_start'));
    }

    /**
     * New Incoming request
     *
     * @return void
     */
    public function provider_schedule($provider){
        //\Log::info("provider_schedule--------" .$provider);

        return $this->sendPushToProvider($provider, trans('api.push.schedule_start'));

    }

    /**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function UserCancellRide($request){
        //\Log::info("UserCancellRide--------" .$request);

        return $this->sendPushToProvider($request->provider_id, trans('api.push.user_cancelled'));
    }


    /**
     * New Ride Accepted by a Driver.
     *
     * @return void
     */
    public function ProviderCancellRide($request){
        //\Log::info("ProviderCancellRide--------" .$request);

        return $this->sendPushToUser($request->user_id, trans('api.push.provider_cancelled'));
    }

    /**
     * Driver Arrived at your location.
     *
     * @return void
     */
    public function Arrived($request){
        //\Log::info("Arrived--------" .$request);

        return $this->sendPushToUser($request->user_id, trans('api.push.arrived'));
    }

    /**
     * Driver Picked the user
     *
     * @return void
     */
    public function Pickedup($request){
        //\Log::info("Pickedup--------" .$request);

        return $this->sendPushToUser($request->user_id, trans('api.push.pickedup'));
    }

    /**
     * Driver Dropped at your location.
     *
     * @return void
     */
    public function Dropped($request){
        //\Log::info("Dropped--------" .$request);

        return $this->sendPushToUser($request->user_id, trans('api.push.dropped').Setting::get('currency').$request->payment->total.' by '.$request->payment_mode);
    }

    /**
     * Driver Completed the ride.
     *
     * @return void
     */
    public function Completed($request){
        //\Log::info("Completed--------" .$request);

        return $this->sendPushToUser($request->user_id, trans('api.push.completed'));
    }

    /**
     * Money added to user wallet.
     *
     * @return void
     */
    public function ProviderNotAvailable($user_id){
        //\Log::info("ProviderNotAvailable--------" .$user_id);

        return $this->sendPushToUser($user_id,trans('api.push.provider_not_available'));
    }

    /**
     * New Incoming request
     *
     * @return void
     */
    public function IncomingRequest($provider){
        //\Log::info("IncomingRequest--------" .$provider);

        return $this->sendPushToProvider($provider, trans('api.push.incoming_request'));

    }
    

    /**
     * Driver Documents verfied.
     *
     * @return void
     */
    public function DocumentsVerfied($provider_id){

        return $this->sendPushToProvider($provider_id, trans('api.push.document_verfied'));
    }


    /**
     * Money added to user wallet.
     *
     * @return void
     */
    public function WalletMoney($user_id, $money){

        return $this->sendPushToUser($user_id, $money.' '.trans('api.push.added_money_to_wallet'));
    }

    /**
     * Money charged from user wallet.
     *
     * @return void
     */
    public function ChargedWalletMoney($user_id, $money){

        return $this->sendPushToUser($user_id, $money.' '.trans('api.push.charged_from_wallet'));
    }

    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToUser($user_id, $push_message){

        try{


            $user = User::findOrFail($user_id);

            if($user->device_token != ""){


               \Log::info('sending push for user : '. $user->first_name);
               // \Log::info($push_message);

                if($user->device_type == 'ios'){
                     if(env('IOS_USER_ENV')=='development'){
                        $crt_user_path=app_path().'/apns/user/User.pem';
                        $crt_provider_path=app_path().'/apns/provider/Provider.pem';
                        $dry_run = true;
                    }
                    else{
                        $crt_user_path=app_path().'/apns/user/User.pem';
                        $crt_provider_path=app_path().'/apns/provider/Provider.pem';
                        $dry_run = false;
                    }
                    
                   $push = new PushNotification('apn');

                    $push->setConfig([
                            'certificate' => $crt_user_path,
                            'passPhrase' => env('IOS_USER_PUSH_PASS', 'apple'),
                            'dry_run' => $dry_run
                        ]);

                   $send=  $push->setMessage([
                            'aps' => [
                                'alert' => [
                                    'body' => $push_message
                                ],
                                'sound' => 'default',
                                'badge' => 1

                            ],
                            'extraPayLoad' => [
                                'custom' => $push_message
                            ]
                        ])
                        ->setDevicesToken($user->device_token)->send();
                        \Log::info('sent');
                    
                    return $send;

                }elseif($user->device_type == 'android'){

                    //\Log::info('Device tojken=================='.$user->device_token);

                    $push = new PushNotification('fcm');
                    $send=  $push->setMessage(['message'=>$push_message])
                        ->setDevicesToken($user->device_token)->send();
                    //\Log::info('sent');
                    
                    return $send;
                       


                }
            }

        } catch(Exception $e){
            //\Log::info($e);   
            return $e;
        }

    }


    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToProvider($provider_id, $push_message){



        try{  

               \Log::info("Send push to Provider================".$provider_id);        

               \Log::info("Send push to Provider================".$push_message);

            $provider = ProviderDevice::where('provider_id',$provider_id)->with('provider')->first();           



            if($provider->token != ""){


                if($provider->type == 'ios'){

                    if(env('IOS_USER_ENV')=='development'){
                        $crt_user_path=app_path().'/apns/user/User.pem';
                        $crt_provider_path=app_path().'/apns/provider/Provider.pem';
                        $dry_run = true;
                    }
                    else{
                        $crt_user_path=app_path().'/apns/user/User.pem';
                        $crt_provider_path=app_path().'/apns/provider/Provider.pem';
                        $dry_run = false;
                    }

                   $push = new PushNotification('apn');
                   $push->setConfig([
                            'certificate' => $crt_provider_path,
                            'passPhrase' => env('IOS_PROVIDER_PUSH_PASS', 'apple'),
                            'dry_run' => $dry_run
                        ]);
                   $send=  $push->setMessage([
                            'aps' => [
                                'alert' => [
                                    'body' => $push_message
                                ],
                                'sound' => 'default',
                                'badge' => 1

                            ],
                            'extraPayLoad' => [
                                'custom' => $push_message
                            ]
                        ])
                        ->setDevicesToken($provider->token)->send();
                
                    \Log::info('sent');
                    return $send;

                }elseif($provider->type == 'android'){

                    \Log::info('Device tojken=================='.$provider->token);
                    
                   $push = new PushNotification('fcm');
                   $send=  $push->setMessage(['message'=>$push_message])
                        ->setApiKey(env('ANDROID_USER_PUSH_KEY'))
                        ->setDevicesToken($provider->token)->send();
                         \Log::info('sent-------------');
                    
                    return $send;
                   
                        

                }
            }


        } catch(Exception $e){  
            //\Log::info($e);         
            return $e;
        }


    }

}
