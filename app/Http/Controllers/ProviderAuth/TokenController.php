<?php

namespace App\Http\Controllers\ProviderAuth;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;

use Tymon\JWTAuth\Exceptions\JWTException;
use App\Notifications\ResetPasswordOTP;

use Auth;
use Config;
use JWTAuth;
use Setting;
use Notification;
use Validator;
use Socialite;
use Carbon\Carbon;
use App\ServiceType;
use App\Helpers\Helper;
use App\Provider;
use App\ProviderDevice;
use App\ProviderService;
use App\Bankdetails;

use App\Document;
use App\ProviderDocument;
use Storage;

class TokenController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        \Log::info($request->all());
        $this->validate($request, [
                'device_id' => 'required',
                // 'bank_name' => 'required',
                // 'license' => 'required',
                // 'sort_code' => 'required',
                // 'account_number' => 'required',
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:providers',
                //'mobile' => 'required|digits_between:6,13',
                'photos'=>'required',
                'mobile' => 'required',
                'password' => 'required|min:6|confirmed',
                'service_type'=>'required',
                // 'service_number'=>'required',
                // 'service_model'=>'required',
            ]);

        // try{    

            $Provider = $request->all();
            $Provider['password'] = bcrypt($request->password);
            if(count($request->photos)){
            $Provider = Provider::create($Provider);

          //  if(Setting::get('demo_mode', 0) == 1) {
                $Provider->update(['status' => 'onboarding']);
                ProviderService::create([
                    'provider_id' => $Provider->id,
                    'service_type_id' => $request->service_type,
                    'status' => 'active',
                    'service_number' => $request->service_number,
                    'service_model' => $request->service_model,
                    // 'license'=>$request->license,
                ]);

          //  }

       
            ProviderDevice::create([
                    'provider_id' => $Provider->id,
                    'udid' => $request->device_id,
                    'token' => $request->device_token,
                    'type' => $request->device_type,
                ]);

            // $Provider_bankdetails = Bankdetails::create([
            //     'provider_id'=> $Provider->id,
            //     'first_name' => $request->first_name,
            //     'last_name' =>$request->last_name,
            //     'bank_name'=>$request->bank_name,
            //     'account_number'=>$request->account_number,
            //     'sort_code'=>$request->sort_code,
            // ]);

            //dd($request);
            
         
                
            foreach ($request['photos'] as $key => $url) {        

                $document_id = $request['id'][$key];    


                 //dd($url);

                    // echo $key .'--'.$url;


                    ProviderDocument::create([
                        'url' => $url->store('provider/documents'),
                        'provider_id' => $Provider->id,
                        'document_id' => $document_id,
                        'status' => 'ASSESSING',
                        //'expires_at'=> Carbon::parse($request->expires_at[$key])->format('Y/m/d'),
                    ]);



                 }
             
                 Helper::site_registermail($Provider);
            return $Provider;

        }
        else{
            return response()->json(['error' => 'Invalid Document parameter'], 401);
        }


        // } catch (QueryException $e) {
        //     if ($request->ajax() || $request->wantsJson()) {
        //         return response()->json(['error' => 'Something went wrong, Please try again later!'], 500);
        //     }
        //     return abort(500);
        // }
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function authenticate(Request $request)
    {

        //\Log::info("Provider Login");
        $this->validate($request, [
                'device_id' => 'required',
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
        \Log::info("Request All---------", $request->all());

        Config::set('auth.providers.users.model', 'App\Provider');

        $credentials = $request->only('email', 'password');


        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'The email address or password you entered is incorrect.'], 401);
            }
        } catch (JWTException $e) {
            //\Log::info($e);
            return response()->json(['error' => 'Something went wrong, Please try again later!'], 500);
        }

        //\Log::info("JWT Finished--------");

        $User = Provider::with('service', 'device')->find(Auth::user()->id);

        $User->access_token = $token;
        $User->currency = Setting::get('currency', '$');
        $User->sos = Setting::get('sos_number', '911');

        if($User->device) {
            ProviderDevice::where('id',$User->device->id)->update([
        
                'udid' => $request->device_id,
                'token' => $request->device_token,
                'type' => $request->device_type,
            ]);
            
        } else {
            ProviderDevice::create([
                    'provider_id' => $User->id,
                    'udid' => $request->device_id,
                    'token' => $request->device_token,
                    'type' => $request->device_type,
                ]);
        }

        return response()->json($User);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
        try {
            ProviderDevice::where('provider_id', $request->id)->update(['udid'=> '', 'token' => '']);
            ProviderService::where('provider_id',$request->id)->update(['status' => 'offline']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

 /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\Response
     */


    public function forgot_password(Request $request){

        $this->validate($request, [
                'email' => 'required|email|exists:providers,email',
            ]);

        try{  
            
            $provider = Provider::where('email' , $request->email)->first();

            $otp = mt_rand(100000, 999999);

            $provider->otp = $otp;
            $provider->save();

            Notification::send($provider, new ResetPasswordOTP($otp));

            return response()->json([
                'message' => 'OTP sent to your email!',
                'provider' => $provider
            ]);

        }catch(Exception $e){
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
                'id' => 'required|numeric|exists:providers,id'
            ]);

        try{

            $Provider = Provider::findOrFail($request->id);
            $Provider->password = bcrypt($request->password);
            $Provider->save();

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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function facebookViaAPI(Request $request) { 
//\Log::info($request);
        $validator = Validator::make(
            $request->all(),
            [
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'accessToken'=>'required',
                'device_id' => 'required',
                'login_by' => 'required|in:manual,facebook,google'
            ]
        );
        
        if($validator->fails()) {

            return response()->json(['status'=>false,'message' => $validator->messages()->all()]);
        }
        $user = Socialite::driver('facebook')->stateless();
        $FacebookDrive = $user->userFromToken( $request->accessToken);

        // $AccessToken = Socialite::driver('facebook')->getAccessTokenResponse($request->accessToken);
       
        // $token = $AccessToken['access_token'];
        // $FacebookDrive = Socialite::driver('facebook')->userFromToken($token);
       
        try{
            $FacebookSql = Provider::where('social_unique_id',$FacebookDrive->id);
            if($FacebookDrive->email !=""){
                
                $FacebookSql->orWhere('email',$FacebookDrive->email);
            }
            
            $AuthUser = $FacebookSql->first();
            if($AuthUser){ 
                $AuthUser->social_unique_id=$FacebookDrive->id;
                $AuthUser->login_by="facebook";
                $AuthUser["mobile"]=$request->mobile;
                $AuthUser->save();  
            }else{   
                
                $AuthUser["email"]=$FacebookDrive->email;
                $name = explode(' ', $FacebookDrive->name, 2);
                $AuthUser["first_name"]=$name[0];
                $AuthUser["last_name"]=isset($name[1]) ? $name[1] : '';
                $AuthUser["password"]=bcrypt($FacebookDrive->id);
                $AuthUser["social_unique_id"]=$FacebookDrive->id;
                $AuthUser["avatar"]=$FacebookDrive->avatar;
                $AuthUser["login_by"]="facebook";
                $AuthUser["mobile"]=$request->mobile;
                $AuthUser = Provider::create($AuthUser);

                if(Setting::get('demo_mode', 0) == 1) {
                    $AuthUser->update(['status' => 'approved']);
                    ProviderService::create([
                        'provider_id' => $AuthUser->id,
                        'service_type_id' => '1',
                        'status' => 'active',
                        'service_number' => '4pp03ets',
                        'service_model' => 'Audi R8',
                    ]);
                }
            }    
            if($AuthUser){ 
                //\Log::info("Provider User Facebook Auth============");
                $userToken = JWTAuth::fromUser($AuthUser);
                $User = Provider::with('service', 'device')->find($AuthUser->id);
                if($User->device) {
                    ProviderDevice::where('id',$User->device->id)->update([
                        
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                    
                } else {
                    ProviderDevice::create([
                        'provider_id' => $User->id,
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                }
                return response()->json([
                            "status" => true,
                            "token_type" => "Bearer",
                            "access_token" => $userToken,
                            'currency' => Setting::get('currency', '$'),
                            'sos' => Setting::get('sos_number', '911')
                        ]);
            }else{
                return response()->json(['status'=>false,'message' => "Invalid credentials!"]);
            }  
        } catch (Exception $e) {
            dd($e);
            return response()->json(['status'=>false,'message' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleViaAPI(Request $request) { 

        $validator = Validator::make(
            $request->all(),
            [
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'accessToken'=>'required',
                'device_id' => 'required',
                'login_by' => 'required|in:manual,facebook,google'
            ]
        );
        
        if($validator->fails()) {
            return response()->json(['status'=>false,'message' => $validator->messages()->all()]);
        }
        $user = Socialite::driver('google')->stateless();
        $GoogleDrive = $user->userFromToken( $request->accessToken);
       
        try{
            $GoogleSql = Provider::where('social_unique_id',$GoogleDrive->id);
            if($GoogleDrive->email !=""){
                $GoogleSql->orWhere('email',$GoogleDrive->email);
            }
            $AuthUser = $GoogleSql->first();
            if($AuthUser){
                $AuthUser->social_unique_id=$GoogleDrive->id;  
                $AuthUser->login_by="google";
                $AuthUser["mobile"]=$request->mobile;
                $AuthUser->save();
            }else{   
                $AuthUser["email"]=$GoogleDrive->email;
                $name = explode(' ', $GoogleDrive->name, 2);
                $AuthUser["first_name"]=$name[0];
                $AuthUser["last_name"]=isset($name[1]) ? $name[1] : '';
                $AuthUser["password"]=($GoogleDrive->id);
                $AuthUser["social_unique_id"]=$GoogleDrive->id;
                $AuthUser["avatar"]=$GoogleDrive->avatar;
                $AuthUser["login_by"]="google";
                $AuthUser["mobile"]=$request->mobile;
                $AuthUser = Provider::create($AuthUser);

                if(Setting::get('demo_mode', 0) == 1) {
                    $AuthUser->update(['status' => 'approved']);
                    ProviderService::create([
                        'provider_id' => $AuthUser->id,
                        'service_type_id' => '1',
                        'status' => 'active',
                        'service_number' => '4pp03ets',
                        'service_model' => 'Audi R8',
                    ]);
                }
            }    
            if($AuthUser){
                $userToken = JWTAuth::fromUser($AuthUser);
                $User = Provider::with('service', 'device')->find($AuthUser->id);
                if($User->device) {
                    ProviderDevice::where('id',$User->device->id)->update([
                        
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                    
                } else {
                    ProviderDevice::create([
                        'provider_id' => $User->id,
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                }
                return response()->json([
                            "status" => true,
                            "token_type" => "Bearer",
                            "access_token" => $userToken,
                            'currency' => Setting::get('currency', '$'),
                            'sos' => Setting::get('sos_number', '911')
                        ]);
            }else{
                return response()->json(['status'=>false,'message' => "Invalid credentials!"]);
            }  
        } catch (Exception $e) {
            return response()->json(['status'=>false,'message' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function refresh_token(Request $request)
    {

        Config::set('auth.providers.users.model', 'App\Provider');

        $Provider = Provider::with('service', 'device')->find(Auth::user()->id);

        try {
            if (!$token = JWTAuth::fromUser($Provider)) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }

        $Provider->access_token = $token;

        return response()->json($Provider);
    }
    
    public function bankDetails()
    {
        try{
          
           $Bank = Bankdetails::where('provider_id',Auth::user()->id)->first();

           return $Bank;

        } catch (Exception $e) {

            return response()->json(['status'=>false,'message' => trans('api.something_went_wrong')]);
        }

    }

    public function bankUpdate(Request $request)
    {
        try{

            $Bank = Bankdetails::where('provider_id',Auth::user()->id)->first();
            if($Bank){
            $Bank->first_name=$request->first_name;
            $Bank->last_name=$request->last_name;
            $Bank->address=$request->address;
            $Bank->postcode=$request->postcode;
            $Bank->sort_code=$request->sort_code;
            $Bank->bank_name=$request->bank_name;
            $Bank->account_number=$request->account_number;
            $Bank->dob=Carbon::parse($request->dob)->format('Y/m/d');
            $Bank->city=$request->city;

            $Bank->save();

            return response()->json(['status'=>true,'message' => "Bank Details Updated Successfully !"]);
        }else{
             Bankdetails::create([
                        'provider_id' => Auth::user()->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'address' => $request->address,
                        'postcode' => $request->postcode,
                        'sort_code' => $request->sort_code,
                        'bank_name' => $request->bank_name,
                        'account_number' => $request->account_number,
                        'dob' => Carbon::parse($request->dob)->format('Y/m/d'),
                        'city' => $request->device_type,
                    ]);
             return response()->json(['status'=>true,'message' => "Bank Details Created Successfully !"]);
        }

        } catch (Exception $e) {

            return response()->json(['status'=>false,'message' => trans('api.something_went_wrong')]);
        }

    }

    public function service()
    {

        try{

            $Service=ServiceType::get();

           
                return response()->json([
                    'service_type' => $Service, 
                   
                     ]);
            

        }catch (Exception $e) {
           
                return response()->json(['error' => trans('api.something_went_wrong')]);
            
        }
    }


     public function doc(){

        $Document=Document::get();

        return response()->json([
                    'document' => $Document, 
                   
                     ]);

     }

     public function documentupload(Request $request){
        $this->validate($request, [
                'document' => 'required',
                'id'=>'required',
            ]);

        \Log::info($request->all());
   try{
         foreach ($request['document'] as $key => $url) {        

                $document_id = $request['id'][$key];    

                    ProviderDocument::create([
                        'url' => $url->store('provider/documents'),
                        'provider_id' => $request->provider_id,
                        'document_id' => $document_id,
                        'status' => 'ASSESSING',
                        //'expires_at'=> Carbon::parse($request->expires_at[$key])->format('Y/m/d'),
                    ]);

           }
         $provider = Provider::where('id',$request->provider_id)->where('status','document')->first();
         $provider->status = 'onboarding';
         $provider->save();
         return response()->json(['status'=>true,'message' => "Document Created Successfully !"]);
       }catch (Exception $e){
        \Log::info($e);
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
       }
        


     }


    /**
     * Show the email availability.
     *
     * @return \Illuminate\Http\Response
     */

    public function verify(Request $request)
    {
        $this->validate($request, [
                'email' => 'required|email|max:255|unique:providers',
            ]);

        try{
            
            return response()->json(['message' => trans('api.email_available')]);

        } catch (Exception $e) {
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function documents()
    {
        $documents = Document::all();
        return $documents;
    }

    public function send_otp(Request $request){

        $this->validate($request, [
            'mobile' => 'required',
            'country_code' => 'required',
            ]);

        try{  
            
            $provider = Provider::where('mobile' , $request->mobile)->first();
            
            if(count($provider) == 0){
                $otp = mt_rand('1111','9999');

                $mobile = $request->country_code . $request->mobile;

                // $status = "success";

                $send_sms = sendTwilioSMS($mobile,$otp);
                \Log::info($send_sms);
                if($send_sms == "success"){

                    return response()->json([
                        'message' => 'OTP sent to your mobile!',
                        'otp' => $otp,
                        'status' => 'success'
                    ]);

                    return response()->json(['status'=> 'success', 'otp' => $otp]);
                }else{

                    return response()->json(['error'=> trans('api.something_went_wrong')], 422);
                }

            }
            else{
                return response()->json(['message' => 'The mobile number you entered is already registered.' , 'error' => "Alredy Registered!" ], 422);
            }
        }catch(Exception $e){
            \Log::info($e);
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function voice_sms(Request $request)
    {
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
    
        $count = Provider::where('otp',$otp)->count();
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

        $User = Provider::where('id', \Auth::user()->id)->update([
            'mobile' => $request->country_code . $request->mobile,
        ]);

        return response()->json(['message' => 'Mobile number updated!'], 200);
    }
}
