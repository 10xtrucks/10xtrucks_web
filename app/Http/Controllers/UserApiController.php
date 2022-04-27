<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use Log;
use Auth;
use Hash;
use Storage;
use Setting;
use Exception;
use Notification;

use Carbon\Carbon;
use App\Http\Controllers\SendPushNotification;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;

use App\Card;
use App\User;
use App\Provider;
use App\GeoFencing;
use App\Settings;
use App\Promocode;
use App\ServiceType;
use App\UserRequests;
use App\RequestFilter;
use App\PromocodeUsage;
use App\ProviderService;
use App\UserRequestRating;
use App\UserRequestDropoff;
use App\Http\Controllers\ProviderResources\TripController;
use App\Http\Controllers\TwilioController;
use Edujugon\PushNotification\PushNotification;

class UserApiController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function signup(Request $request)
    {
        $this->validate($request, [
                'social_unique_id' => ['required_if:login_by,facebook,google','unique:users'],
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'device_id' => 'required',
                'login_by' => 'required|in:manual,facebook,google',
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'mobile' => 'required',
                'password' => 'required|min:6',


            ]);

        try{
            
            $User = $request->all();

            $User['payment_mode'] = 'PAYSTACK';
            $User['password'] = bcrypt($request->password);
            $User = User::create($User);
            Helper::site_registermail($User);
            return $User;
        } catch (Exception $e) {
//dd($e);
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function help_details(Request $request){

        try{

            if($request->ajax()) {
                return response()->json([
                    'contact_number' => Setting::get('contact_number',''), 
                    'contact_email' => Setting::get('contact_email','')
                     ]);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
        try {
            User::where('id', $request->id)->update(['device_id'=> '', 'device_token' => '']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function change_password(Request $request){

        $this->validate($request, [
                'password' => 'required|confirmed|min:6',
                'old_password' => 'required',
            ]);

        $User = Auth::user();

        if(Hash::check($request->old_password, $User->password))
        {
            $User->password = bcrypt($request->password);
            $User->save();

            if($request->ajax()) {
                return response()->json(['message' => trans('api.user.password_updated')]);
            }else{
                return back()->with('flash_success', 'Password Updated');
            }

        } else {
            return response()->json(['error' => trans('api.user.incorrect_password')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update_location(Request $request){

        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

        if($user = User::find(Auth::user()->id)){

            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();

            return response()->json(['message' => trans('api.user.location_updated')]);

        }else{

            return response()->json(['error' => trans('api.user.user_not_found')], 500);

        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function details(Request $request){

        $this->validate($request, [
            'device_type' => 'in:android,ios',
        ]);

        try{

            if($user = User::find(Auth::user()->id)){

                if($request->has('device_token')){
                    $user->device_token = $request->device_token;
                }

                if($request->has('device_type')){
                    $user->device_type = $request->device_type;
                }

                if($request->has('device_id')){
                    $user->device_id = $request->device_id;
                }

                $user->save();

                $user->currency = Setting::get('currency');
                $user->sos = Setting::get('sos_number', '911');
                return $user;

            } else {
                return response()->json(['error' => trans('api.user.user_not_found')], 500);
            }
        }
        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update_profile(Request $request)
    {

        $this->validate($request, [
                'first_name' => 'required|max:255',
                // 'last_name' => 'max:255',
                'email' => 'email|unique:users,email,'.Auth::user()->id,
                'mobile' => 'required',
                'picture' => 'mimes:jpeg,bmp,png',
                // 'user_type' => 'required|in:BUSINESSUSER,NORMAL',
            ]);

         try {

            $user = User::findOrFail(Auth::user()->id);

            if($request->has('first_name')){ 
                $user->first_name = $request->first_name;
            }
            
            if($request->has('last_name')){
                $user->last_name = $request->last_name;
            }
            
            if($request->has('email')){
                $user->email = $request->email;
            }
        
            if($request->has('mobile')){
                $user->mobile = $request->country_code . $request->mobile;
            }

            if ($request->picture != "") {
                Storage::delete($user->picture);
                $user->picture = $request->picture->store('user/profile');
            }
            if($request->has('user_type')){
                $user->user_type = $request->user_type;
            }

            $user->save();

            if($request->ajax()) {
                return response()->json($user);
            }else{
                return back()->with('flash_success', trans('api.user.profile_updated'));
            }
        }

        catch (ModelNotFoundException $e) {
             return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function services(Request $request) {

        if($request->has('weight')){
            $weight = array_sum($request->weight);
            $serviceList = ServiceType::where('maximum_weight' , '>=', $weight)->get();
        }else{
            $serviceList = ServiceType::all();
        }
        if($serviceList) {
            if($request->ajax()){
             return $serviceList;   
             }else{
                return view('user.car_detail', compact('serviceList'));   
             }
            

        } else {
            return response()->json(['error' => trans('api.services_not_found')], 500);
        }

    }

    public function chatPush(Request $request){

        $this->validate($request,[
                'user_id' => 'required|numeric',
                'message' => 'required',
            ]);       

        try{
          
            $user_id=$request->user_id;
            $message=$request->message;
            $sender=$request->sender;

            /*$message = \PushNotification::Message($message,array(
            'badge' => 1,
            'sound' => 'default',
            'custom' => array('type' => 'chat')
            ));*/

            //$push = new PushNotification('fcm');
            //$message=  $push->setMessage(['message' => $message,'badge' => 1,'sound' => 'default','custom' => array('type' => 'chat')]);
               \Log::info($user_id);
             \Log::info($message);

            (new SendPushNotification)->sendPushToProvider($user_id, $message);
             \Log::info($user_id);
             \Log::info($message);
            //(new SendPushNotification)->sendPushToUser($user_id, $message);         

            return response()->json(['success' => 'true'], 200);

        } catch(Exception $e) {
         //   dd($e);
            \Log::info($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function send_request(Request $request) {

        $this->validate($request, [
            's_latitude.*' => 'required|numeric',
            'd_latitude.*' => 'required|numeric',
            's_longitude.*' => 'required|numeric',
            'd_longitude.*' => 'required|numeric',
            'reseiver_name'=>'required',
            'reseiver_mobile'=>'required',
            'service_type' => 'required|numeric|exists:service_types,id',
            'service_items.*' => 'required',
            'promo_code' => 'exists:promocodes,promo_code',
            'distance' => 'required|numeric',
            'use_wallet' => 'numeric',
            'payment_mode' => 'required|in:CASH,CARD,PAYPAL,PAYFAST,PAYSTACK,BOL',
            'card_id' => ['required_if:payment_mode,CARD','exists:cards,card_id,user_id,'.Auth::user()->id],
        ]);

        // foreach ($request['s_latitude'] as $key => $value) {
        //     $long = $request->s_longitude[$key];
        //     $geo_check = $this->poly_check_new((round($value,6)),(round($long,6)));


        // }
        // if($geo_check=='no')
        // {
        //     if($request->ajax()) {
        //         return response()->json(['error' => 'Service Not Available in this Location'], 422);
        //     } else {
        //         return redirect('dashboard')->with('flash_error', trans('api.ride.geo_error'));
        //     }
        // }

        $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();


        if($ActiveRequests > 0) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.ride.request_inprogress')], 500);
            } else {
                return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later');
            }
        }

        if($request->has('schedule_date') && $request->has('schedule_time')){
            $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);

            $CheckScheduling = UserRequests::where('status','SCHEDULED')
                            ->where('user_id', Auth::user()->id)
                            ->whereBetween('schedule_at',[$beforeschedule_time,$afterschedule_time])
                            ->count();


            if($CheckScheduling > 0){
                if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                }else{
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }
            }

        }

        $distance = Setting::get('provider_search_radius', '10');
        $latitude = array_first($request->s_latitude);
        $longitude = array_first($request->s_longitude);
        $service_type = $request->service_type;
        $weight = array_sum($request->weight);

        $Providers = Provider::with('service')->with('service.service_type')
            ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"),'id')
            ->where('status', 'approved')
            ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
            ->whereHas('service', function($query) use ($service_type){
                        $query->where('status','active');
                        $query->where('service_type_id',$service_type);
                        //$query->where('maximum_weight', '<=' ,$weight);
            })
            ->orderBy('distance')
            ->get();

        // List Providers who are currently busy and add them to the filter list.

        if(count($Providers) == 0) {
            if($request->ajax()) {
                // Push Notification to User
                return response()->json(['message' => trans('api.ride.no_providers_found')]); 
            }else{
                return back()->with('flash_success', 'No Providers Found! Please try again.');
            }
        }

        try{

            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".array_first($request->s_latitude).",".array_first($request->s_longitude)."&destination=".array_first($request->d_latitude).",".array_first($request->d_longitude)."&mode=driving&key=".Setting::get('google_map_key');
            

            $json = curl($details);

            $details = json_decode($json, TRUE);
            $service_item = implode(',',$request->service_items);
            $reseiver_name = implode(',',$request->reseiver_name);
            $reseiver_mobile = implode(',',$request->reseiver_mobile);
            $total_weight = implode(',',$request->weight);
            $fragile = 0;
            $comment = null;
             if($request->fragile){
                // $fragile = implode(',', $request->fragile);
             }
            if($request->comment){
                $comment = implode(',', $request->comment);
            }
            \Log::info(iconv(mb_detect_encoding(array_first($request->s_address), mb_detect_order(), true), "UTF-8//IGNORE", array_first($request->s_address)));
            $route_key = $details['routes'][0]['overview_polyline']['points'];

            $UserRequest = new UserRequests;
            $UserRequest->booking_id = Helper::generate_booking_id();
            $UserRequest->user_id = Auth::user()->id;
            if(Setting::get('broadcast_request',0) == 0){
                $UserRequest->current_provider_id = $Providers[0]->id;
            }else{
                $UserRequest->current_provider_id = 0;
            }
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->payment_mode = $request->payment_mode;
            
            $UserRequest->status = 'SEARCHING';
            $UserRequest->reseiver_mobile=$reseiver_mobile;
            $UserRequest->reseiver_name=$reseiver_name;
            $UserRequest->s_address = iconv(mb_detect_encoding(array_first($request->s_address), mb_detect_order(), true), "UTF-8//IGNORE", array_first($request->s_address));
            $UserRequest->s_latitude = array_first($request->s_latitude);
            $UserRequest->s_longitude = array_first($request->s_longitude);
            
            $UserRequest->d_address = iconv(mb_detect_encoding(array_last($request->d_address), mb_detect_order(), true), "UTF-8//IGNORE", array_last($request->d_address));
            $UserRequest->d_latitude = array_last($request->d_latitude);
            $UserRequest->d_longitude = array_last($request->d_longitude);
            $UserRequest->distance = $request->distance;
            if(Setting::get('track_distance') == 1){
                $UserRequest->is_track = 'YES';
            }
            $UserRequest->service_items = $service_item;
            $UserRequest->total_weight = $total_weight;
            $UserRequest->fragile = $fragile;
            $UserRequest->comment = $comment;
            $UserRequest->destination_log = json_encode([['latitude' => $UserRequest->d_latitude, 'longitude' => $request->d_longitude, 'address' => $request->d_address]]);
            
            $otp = rand(1000,9999);
            $sender_otp = rand(1000,9999);
            $data['otp'] = $otp;
            $data['mobile'] = \Auth::user()->mobile;     
            $data['message'] = 'Please share this OTP '.$otp.'  with your ' .Setting::get('site_title', '10XTrucks'). ' courier agent to send your package';     
           // (new TwilioController)->sendSms($data);
            $UserRequest->otp = $otp;
            $UserRequest->sender_otp = $sender_otp;

            if(Auth::user()->wallet_balance > 0){
                $UserRequest->use_wallet = $request->use_wallet ? : 0;
            }

            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->route_key = $route_key;

            if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0){
                $UserRequest->surge = 1;
            }

            if($request->has('schedule_date') && $request->has('schedule_time')){
                $UserRequest->schedule_at = date("Y-m-d H:i:s",strtotime("$request->schedule_date $request->schedule_time"));
            }

            if(Setting::get('broadcast_request',0) == 0){
                //Log::info('New Request id : '. $UserRequest->id .' Assigned to provider : '. $UserRequest->current_provider_id);
                (new SendPushNotification)->IncomingRequest($Providers[0]->id);
            }


            $UserRequest->save();
            DB::connection()->enableQueryLog();
            $queries = DB::getQueryLog();
            $last_query = end($queries);
            // \Log::info($last_query);

            //Inserting all the request data's in Dropoff Table

            $user_request_id = $UserRequest->id  ;

            $i=1;
            $k=0; 
            foreach($request->s_latitude as $key => $value) {
                      
                if($i==1)
                {
                    
                    $UserRequestDropoff = new UserRequestDropoff;
                    $UserRequestDropoff->otp = rand(1000,9999);
                    $UserRequestDropoff->user_request_id = $user_request_id;
                    $UserRequestDropoff->service_items = $request->service_items[$key];
                    $UserRequestDropoff->total_weight = $request->weight[$key]; 
                    $UserRequestDropoff->fragile = isset($request->fragile[$key])?$request->fragile[$key]:0; 
                    $UserRequestDropoff->comment = isset($request->comment[$key]) ? $request->comment[$key] : null; 
                    $UserRequestDropoff->s_address = iconv(mb_detect_encoding($request->s_address[$key], mb_detect_order(), true), "UTF-8//IGNORE", $request->s_address[$key]);
                    $UserRequestDropoff->d_address = iconv(mb_detect_encoding($request->d_address[$key], mb_detect_order(), true), "UTF-8//IGNORE", $request->d_address[$key]);
                    $UserRequestDropoff->s_latitude = $value;
                    $UserRequestDropoff->s_longitude = $request->s_longitude[$key];
                    $UserRequestDropoff->d_latitude = $request->d_latitude[$key];
                    $UserRequestDropoff->d_longitude = $request->d_longitude[$key]; 
                    $UserRequestDropoff->status = $UserRequest->status;
                    $UserRequestDropoff->reseiver_mobile=isset($request->reseiver_mobile[$key]) ? (@$request->reseiver_country_code[$key].$request->reseiver_mobile[$key]) : null; 
                    $UserRequestDropoff->reseiver_name=isset($request->reseiver_name[$key]) ? $request->reseiver_name[$key] : null;
                    $UserRequestDropoff->save();
                }
                else
                {

                    $UserRequestDropoff = new UserRequestDropoff;

                    $UserRequestDropoff->user_request_id = $user_request_id;
                    $UserRequestDropoff->service_items = $request->service_items[$key]; 
                    $UserRequestDropoff->otp = rand(1000,9999);
                    $UserRequestDropoff->total_weight = $request->weight[$key]; 
                    $UserRequestDropoff->fragile = isset($request->fragile[$key])?$request->fragile[$key]:0; 
                    $UserRequestDropoff->comment = isset($request->comment[$key]) ? $request->comment[$key] : null; 
                    $UserRequestDropoff->s_address = iconv(mb_detect_encoding($request->d_address[$k], mb_detect_order(), true), "UTF-8//IGNORE", $request->d_address[$k]);
                    $UserRequestDropoff->d_address = iconv(mb_detect_encoding($request->d_address[$key], mb_detect_order(), true), "UTF-8//IGNORE", $request->d_address[$key]);
                    $UserRequestDropoff->s_latitude = $request->d_latitude[$k];
                    $UserRequestDropoff->s_longitude = $request->d_longitude[$k];
                    $UserRequestDropoff->d_latitude = $request->d_latitude[$key];
                    $UserRequestDropoff->d_longitude = $request->d_longitude[$key]; 
                    $UserRequestDropoff->status = $UserRequest->status;
                    $UserRequestDropoff->reseiver_mobile=isset($request->reseiver_mobile[$key]) ? (@$request->reseiver_country_code[$key].$request->reseiver_mobile[$key]) : null; 
                    $UserRequestDropoff->reseiver_name=isset($request->reseiver_name[$key]) ? $request->reseiver_name[$key] : null;
                    $UserRequestDropoff->save();
                }

                $k++;
                $i++;
            }
            
            //Log::info('New Request id : '. $UserRequest->id .' Assigned to provider : '. $UserRequest->current_provider_id);
           
            // update payment mode 

            User::where('id',Auth::user()->id)->update(['payment_mode' => $request->payment_mode]);

            if($request->has('card_id')){

                Card::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
                Card::where('card_id',$request->card_id)->update(['is_default' => 1]);
            }


            foreach ($Providers as $key => $Provider) {
                if(Setting::get('broadcast_request',0) == 1){
                       (new SendPushNotification)->IncomingRequest($Provider->id); 
                    }
                $Filter = new RequestFilter;
                // Send push notifications to the first provider
                // incoming request push to provider
                $Filter->request_id = $UserRequest->id;
                $Filter->provider_id = $Provider->id; 
                $Filter->save();
            }

            if($request->ajax()) {
                return response()->json([
                        'message' => 'New request Created!',
                        'request_id' => $UserRequest->id,
                        'current_provider' => $UserRequest->current_provider_id,
                    ]);
            }else{
                return redirect('dashboard');
            }

        } catch (Exception $e) {
            \Log::info($e);
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }

    public function poly_check_new($s_latitude,$s_longitude)
    {
        //dd($s_latitude.$s_longitude);
        $range_data = GeoFencing::get();
        //dd($range_data);

        $yes = $no =   [];

        $longitude_x = $s_latitude;

        $latitude_y =  $s_longitude;

        foreach($range_data as $ranges)
        {
            // $ranges  = Setting::get('service_range');

            $vertices_x = $vertices_y = [];

            $range_values = json_decode($ranges['ranges'],true);
            //dd($range_values);
            foreach($range_values as $range ){

                $vertices_x[] = $range['lat'];

                $vertices_y[] = $range['lng'];

            }
            //   $pointLocation = new pointLocation();
            $points_polygon = count($vertices_x)-1; 
            if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){

                $yes[] = 1;
            }else{
                $no[] = 1;
            }
        }
        if(count($yes)!=0)
        {
            return 'yes';
        }
        else
        {
            return 'no';
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function cancel_request(Request $request) {
        $this->validate($request, [
            'request_id' => 'required|numeric|exists:user_requests,id,user_id,'.Auth::user()->id,
        ]);

        try{
            $UserRequest = UserRequests::findOrFail($request->request_id);

            if($UserRequest->status == 'CANCELLED')
            {
                if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_cancelled')], 500); 
                }else{
                    return back()->with('flash_error', 'Request is Already Cancelled!');
                }
            }

            if(in_array($UserRequest->status, ['SEARCHING','STARTED','ARRIVED','SCHEDULED'])) {

                if($UserRequest->status != 'SEARCHING'){
                    $this->validate($request, [
                        'cancel_reason'=> 'max:255',
                    ]);
                }

                $UserRequest->status = 'CANCELLED';
                $UserRequest->cancel_reason = $request->cancel_reason;
                $UserRequest->cancelled_by = 'USER';
                $UserRequest->save();

                RequestFilter::where('request_id', $UserRequest->id)->delete();

                if($UserRequest->status != 'SCHEDULED'){
                    if($UserRequest->provider_id != 0){
                        ProviderService::where('provider_id',$UserRequest->provider_id)->update(['status' => 'active']);
                    }
                }

                // Send Push Notification to User
                (new SendPushNotification)->UserCancellRide($UserRequest);

                if($request->ajax()) {
                    return response()->json(['message' => trans('api.ride.ride_cancelled')]); 
                }else{
                    return redirect('dashboard')->with('flash_success','Request Cancelled Successfully');
                }

            } else {
                if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_onride')], 500); 
                }else{
                    return back()->with('flash_error', 'Service Already Started!');
                }
            }
        }catch (ModelNotFoundException $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }else{
                return back()->with('flash_error', 'No Request Found!');
            }
        }
    }

    /**
     * Show the request status check.
     *
     * @return \Illuminate\Http\Response
     */

    public function request_status_check() {

        try{
            $check_status = ['CANCELLED', 'SCHEDULED'];

            $UserRequests = UserRequests::UserRequestStatusCheck(Auth::user()->id, $check_status)
                                        ->get()
                                        ->toArray();
            $UserRequestid = UserRequests::UserRequestStatusCheck(Auth::user()->id, $check_status)->get();
           
            $search_status = ['SEARCHING','SCHEDULED','BIDDING'];

            $UserRequestsFilter = UserRequests::UserRequestAssignProvider(Auth::user()->id,$search_status)->get(); 

            $UserRequestDropoff = UserRequestDropoff::whereIn('user_request_id',$UserRequestid)->get();

            

            // Log::info($UserRequestsFilter);

            $Timeout = Setting::get('provider_select_timeout', 180);

            if(!empty($UserRequestsFilter)){
                for ($i=0; $i < sizeof($UserRequestsFilter); $i++) {
                    $ExpiredTime = $Timeout - (time() - strtotime($UserRequestsFilter[$i]->assigned_at));
                    if($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime < 0) {
                        $Providertrip = new TripController();
                        $Providertrip->assign_next_provider($UserRequestsFilter[$i]->id);
                    }else if($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime > 0){
                        break;
                    }
                }
            }

            return response()->json(['data' => $UserRequests,'userdrop' =>$UserRequestDropoff]);

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function rate_provider(Request $request) {

        $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id,user_id,'.Auth::user()->id,
                'rating' => 'required|integer|in:1,2,3,4,5',
                'comment' => 'max:255',
            ]);
    
        $UserRequests = UserRequests::where('id' ,$request->request_id)
                ->where('status' ,'COMPLETED')
                ->where('paid', 0)
                ->first();

        if ($UserRequests) {
            if($request->ajax()){
                return response()->json(['error' => trans('api.user.not_paid')], 500);
            } else {
                return back()->with('flash_error', 'Service Already Started!');
            }
        }

        try{

            $UserRequest = UserRequests::findOrFail($request->request_id);
            
            if($UserRequest->rating == null) {
                UserRequestRating::create([
                        'provider_id' => $UserRequest->provider_id,
                        'user_id' => $UserRequest->user_id,
                        'request_id' => $UserRequest->id,
                        'user_rating' => $request->rating,
                        'user_comment' => $request->comment,
                    ]);
            } else {
                $UserRequest->rating->update([
                        'user_rating' => $request->rating,
                        'user_comment' => $request->comment,
                    ]);
            }

            $UserRequest->user_rated = 1;
            $UserRequest->save();

            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('user_rating');

            Provider::where('id',$UserRequest->provider_id)->update(['rating' => $average]);

            // Send Push Notification to Provider 
            if($request->ajax()){
                return response()->json(['message' => trans('api.ride.provider_rated')]); 
            }else{
                return redirect('dashboard')->with('flash_success', 'Driver Rated Successfully!');
            }
        } catch (Exception $e) {
            if($request->ajax()){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something went wrong');
            }
        }

    } 


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function trips() {
    
        try{
            $UserRequests = UserRequests::UserTrips(Auth::user()->id)->get();
            if(!empty($UserRequests)){
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x191919|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function estimated_fare(Request $request){
        $this->validate($request,[
            's_latitude.*' => 'required|numeric',
            's_longitude.*' => 'required|numeric',
            'd_latitude.*' => 'required|numeric',
            'd_longitude.*' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
        ]);
        try{
            foreach ($request->s_latitude as $key => $value) {
                $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$value.",".$request->s_longitude[$key]."&destinations=".$request->d_latitude[$key].",".$request->d_longitude[$key]."&mode=driving&sensor=false&key=".Setting::get('google_map_key');

                $json = curl($details);

                $details = json_decode($json, TRUE);
                \Log::info($details);
                $meter = $details['rows'][0]['elements'][0]['distance']['value'];
                $time = $details['rows'][0]['elements'][0]['duration']['text'];
                $seconds = $details['rows'][0]['elements'][0]['duration']['value'];

                // $kilometer[] = round($meter/1609);
                $kilometer[] = round($meter/1000,1); //TKM
                $minutes[] = round($seconds/60);
            }

            $kilometer=array_sum($kilometer);
            $minutes=array_sum($minutes);

            $tax_percentage = Setting::get('tax_percentage');
            $commission_percentage = Setting::get('commission_percentage');
            $service_type = ServiceType::findOrFail($request->service_type);
            
            $price = $service_type->fixed;

            if($service_type->calculator == 'MIN') {
                $price += $service_type->minute * $minutes;
            } else if($service_type->calculator == 'HOUR') {
                $price += $service_type->minute * 60;
            } else if($service_type->calculator == 'DISTANCE') {
                $price += ($kilometer * $service_type->price);
            } else if($service_type->calculator == 'DISTANCEMIN') {
                $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes);
            } else if($service_type->calculator == 'DISTANCEHOUR') {
                $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes * 60);
            } else {
                $price += ($kilometer * $service_type->price);
            }

            $tax_price = ( $tax_percentage/100 ) * $price;
            $total = $price + $tax_price;


            $ActiveProviders = ProviderService::AvailableServiceProvider($request->service_type)->get()->pluck('provider_id');

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->s_latitude[1];
            $longitude = $request->s_longitude[1];

            
            $Providers = Provider::whereIn('id', $ActiveProviders)
                ->where('status', 'approved')
                ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->get();
            

            $surge = 0;
            
            if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0){
                $surge_price = (Setting::get('surge_percentage')/100) * $total;
                $total += $surge_price;
                $surge = 1;
            }

            $comment = [];
            if($request->has('comment')){
                foreach($request->comment as $key => $comments){
                    if($comments != ""){
                        $comment[$key] = $comments;
                    }
                }
            }

            return response()->json([
                'estimated_fare' => round($total,2), 
                'distance' => $kilometer,
                'time' => $time,
                'surge' => $surge,
                'surge_value' => '1.4X',
                'tax_price' => $tax_price,
                'base_price' => $service_type->fixed,
                'wallet_balance' => Auth::user()->wallet_balance,
                'service_items'=> $request->service_items,
                'weight' => $request->weight,
                'fragile' => $request->fragile,
                'comment' => $comment
            ]);

        } catch(Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function trip_details(Request $request) {

        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);
    
        try{
            $UserRequests = UserRequests::UserTripDetails(Auth::user()->id,$request->request_id)->get();
            
            if(!empty($UserRequests)){
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x191919|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * get all promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public function promocodes() {
        try{
            $this->check_expiry();

            return PromocodeUsage::Active()
                    ->where('user_id', Auth::user()->id)
                    ->with('promocode')
                    ->get();

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    } 


    public function check_expiry(){
        try{
            $Promocode = Promocode::all();
            foreach ($Promocode as $index => $promo) {
                if(date("Y-m-d") > $promo->expiration){
                    $promo->status = 'EXPIRED';
                    $promo->save();
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'EXPIRED']);
                }else{
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'ADDED']);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * add promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public function add_promocode(Request $request) {

        $this->validate($request, [
                'promocode' => 'required|exists:promocodes,promo_code',
            ]);

        try{

            $find_promo = Promocode::where('promo_code',$request->promocode)->first();

            if($find_promo->status == 'EXPIRED' || (date("Y-m-d") > $find_promo->expiration)){

                if($request->ajax()){

                    return response()->json([
                        'message' => trans('api.promocode_expired'), 
                        'code' => 'promocode_expired'
                    ]);

                }else{
                    return back()->with('flash_error', trans('api.promocode_expired'));
                }

            }elseif(PromocodeUsage::where('promocode_id',$find_promo->id)->where('user_id', Auth::user()->id)->where('status','ADDED')->count() > 0){

                if($request->ajax()){

                    return response()->json([
                        'message' => trans('api.promocode_already_in_use'), 
                        'code' => 'promocode_already_in_use'
                        ]);

                }else{
                    return back()->with('flash_error', 'Promocode Already in use');
                }

            }else{

                $promo = new PromocodeUsage;
                $promo->promocode_id = $find_promo->id;
                $promo->user_id = Auth::user()->id;
                $promo->status = 'ADDED';
                $promo->save();

                if($request->ajax()){

                    return response()->json([
                            'message' => trans('api.promocode_applied') ,
                            'code' => 'promocode_applied'
                         ]); 

                }else{
                    return back()->with('flash_success', trans('api.promocode_applied'));
                }
            }

        }

        catch (Exception $e) {
            if($request->ajax()){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something Went Wrong');
            }
        }

    } 

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trips() {
    
        try{
            $UserRequests = UserRequests::UserUpcomingTrips(Auth::user()->id)->get();
            if(!empty($UserRequests)){
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x000000|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trip_details(Request $request) {

         $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
            ]);
    
        try{
            $UserRequests = UserRequests::UserUpcomingTripDetails(Auth::user()->id,$request->request_id)->get();
            if(!empty($UserRequests)){
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x000000|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Show the nearby providers.
     *
     * @return \Illuminate\Http\Response
     */

    public function show_providers(Request $request) {

        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'service' => 'numeric|exists:service_types,id',
            ]);

        try{

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            if($request->has('service')){

                $ActiveProviders = ProviderService::AvailableServiceProvider($request->service)
                                    ->get()->pluck('provider_id');

                $Providers = Provider::whereIn('id', $ActiveProviders)
                    ->where('status', 'approved')
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get();

            } else {

                $ActiveProviders = ProviderService::where('status', 'active')
                                    ->get()->pluck('provider_id');

                $Providers = Provider::whereIn('id', $ActiveProviders)
                    ->where('status', 'approved')
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get();
            }

        
            return $Providers;

        } catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }


    /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\Response
     */


    public function forgot_password(Request $request){

        $this->validate($request, [
                'email' => 'required|email|exists:users,email',
            ]);

        try{

            //\Log::info("User Forget Password===========".$request->email);  
            
            $user = User::where('email' , $request->email)->first();

            $otp = mt_rand(100000, 999999);

            $user->otp = $otp;
            $user->save();

            Notification::send($user, new ResetPasswordOTP($otp));

            return response()->json([
                'message' => 'OTP sent to your email!',
                'user' => $user
            ]);

        }catch(Exception $e){
            //\Log::info($e);
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */

    public function reset_password(Request $request){

        $this->validate($request, [
                'password' => 'required|confirmed|min:6',
                'id' => 'required|numeric|exists:users,id'
            ]);

        try{

            $User = User::findOrFail($request->id);
            $User->password = bcrypt($request->password);
            $User->save();

            if($request->ajax()) {
                return response()->json(['message' => 'Password Updated']);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * help Details.
     *
     * @return \Illuminate\Http\Response
     */

    // public function help_details(Request $request){

    //     try{

    //         if($request->ajax()) {
    //             return response()->json([
    //                 'contact_number' => Setting::get('contact_number',''), 
    //                 'contact_email' => Setting::get('contact_email','')
    //                  ]);
    //         }

    //     }catch (Exception $e) {
    //         if($request->ajax()) {
    //             return response()->json(['error' => trans('api.something_went_wrong')]);
    //         }
    //     }
    // }


    /**
     * Show the email availability.
     *
     * @return \Illuminate\Http\Response
     */

    public function verify(Request $request)
    {
        $this->validate($request, [
                'email' => 'required|email|max:255|unique:users',
            ]);

        try{
            
            return response()->json(['message' => trans('api.email_available')]);

        } catch (Exception $e) {
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

     public function send_otp(Request $request)
    {
        try{

            $user = User::where('mobile',$request->mobile)->first();
            
            if(count($user) == 0){

                $otp = mt_rand('1111','9999');

                $mobile = $request->country_code . $request->mobile;

                // $status = "success";

                $send_sms = sendTwilioSMS($mobile,$otp);
               \Log::info($send_sms);
                if($send_sms == "success"){

                    return response()->json(['status'=> 'success', 'otp' => $otp]);
                }else{

                    return response()->json(['status'=> 'failure', 'message'=> $send_sms]);
                }

            }else{
                return response()->json(['status'=> 'Already Registered.']); 
            }
            
        }catch(Exception $e){
            \Log::info($e);
            return response()->json(['error'=>'Something Went Wrong']);

        }

    
    }

    public function voice_sms(Request $request)
    {
        // dd($request->username);
        $this->validate($request, [
                
                'mobile' => 'required|numeric',
                'country_code' => 'required',
                
            ]);

            $mobile = $request->mobile;
            $mobileno = $request->country_code.$mobile;

            $otp = $this->otp_generate();

            voicesms($mobileno,$otp);

            return response()->json(['otp' => $otp]);

    }

    public function otp_generate()
    {
        $otp = mt_rand(1000, 9999);
    
        $count = User::where('otp',$otp)->count();
        if($count!=0)
        {
           $otp = $this->otp_generate();
        }

        return $otp;
    }

    public function updateMobile(Request $request) {
        $this->validate($request, [
            'country_code' => 'required',
            'mobile' => 'required',
        ]);

        $User = User::where('id', \Auth::user()->id)->update([
            'mobile' => $request->country_code . $request->mobile,
        ]);

        return response()->json(['message' => 'Mobile number updated!'], 200);
    }

}
