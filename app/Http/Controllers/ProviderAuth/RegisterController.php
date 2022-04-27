<?php

namespace App\Http\Controllers\ProviderAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

use Setting;
use Validator;
use Storage;

use App\Provider;
use App\ProviderService;
use App\Document;
use App\ProviderDocument;
use Carbon\carbon;
use App\Helpers\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\TwilioController;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/provider/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('provider.guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'first_name' => 'required|max:255',
             'last_name' => 'required|max:255',
            'phone_number' => 'required',
            'country_code' => 'required',
            'email' => 'required|email|max:255|unique:providers',
            'password' => 'required|min:6|confirmed',
            'service_type' => 'required',
            'service_number' => 'required',
            'service_model' => 'required',
            'document'=>'required',
            'expires_at'=>'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Provider
     */
    protected function create(array $data)
    {
        $Provider = Provider::create([
            'first_name' => $data['first_name'],
             'last_name' => $data['last_name'],
            'email' => $data['email'],
            'mobile' => $data['country_code'].$data['phone_number'],
            'password' => bcrypt($data['password']),
        ]);

        $provider_service = ProviderService::create([
            'provider_id' => $Provider->id,
            'service_type_id' => $data['service_type'],
            'service_number' => $data['service_number'],
            'service_model' => $data['service_model'],
            //  'license'=>$data['license'],
        ]);

        // $Provider_bankdetails = Bankdetails::create([
        //     'provider_id'=> $Provider->id,
        //     'first_name' => $data['first_name'],
        //     'last_name' => $data['last_name'],
        //     'bank_name'=>$data['bank_name'],
        //     'account_number'=>$data['account_number'],
        //     'sort_code'=>$data['sort_code'],
        // ]);
       

        if(Setting::get('demo_mode', 0) == 1) {
            $Provider->update(['status' => 'approved']);
            $provider_service->update([
                'status' => 'active',
            ]);
        }

        // for($i=0; $i<sizeof($data['document']);$i++)
        // {   
       

        //     ProviderDocument::create([
        //             'url' => Storage::putFile('provider/profile', $data['document'][$i], 'public'),
        //             'provider_id' => $Provider->id,
        //             'document_id'=>$data['id'][$i],
        //             'status' => 'ASSESSING',
        //             'expires_at'=> Carbon::parse($data['expires_at'][$i])->format('Y/m/d'),
        //         ]);
            
        // }
         foreach ($data['document'] as $key => $url) {        

                $document_id = $data['id'][$key];    


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

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $DriverDocuments=Document::get();
        return view('provider.auth.register',compact('DriverDocuments'));
        //return view('provider.auth.register');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('provider');
    }

    public function OTP(Request $request)
    {   
        
   
        $messages = [                    
                    'mobile.unique' => 'You are already Registered',
                ];
        if($request->has('login_by'))
        {
            $this->validate($request, [
                'mobile' => 'required|unique:providers|min:6',
                'login_by' => 'required',
                'accessToken' => 'required'
            ],$messages);  
        }
        else
        {

            $this->validate($request, [
                'mobile' => 'required|unique:providers|min:6'
            ],$messages); 

        } 



       try {

            $data = $request->all();
            if($request->has('login_by')){                
               
                //dd($social_data);
                if($social_data){
                    return response()->json([
                    'error' => trans('form.socialuser_exist'),
                ], 422); 
                }
           }elseif(Provider::where('mobile',$data['mobile'])->first()){

                return response()->json([
                    'error' => trans('form.mobile_exist'),
                ], 422); 
            }

            $newotp = rand(1000,9999);
            $data['otp'] = $newotp;
            $data['phone'] = $data['mobile'];     
            $data['message'] = trans('unauthorized.email.your_otp').' '.$newotp;     
            $check =Provider::wheremobile($data['phoneonly'])->first();           

            if(count($check)>0) 
            {
                 return response()->json(['error' => 'Mobile Number Already Exist'], 200); 
            }   
            else
            {
                
                  (new TwilioController)->sendSms($data);
                return response()->json([
                    'message' => 'OTP Sent',
                    'otp' => $newotp
                ]);
       
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('form.whoops')], 500);
        }
    }
}
