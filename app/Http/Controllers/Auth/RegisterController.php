<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Http\Controllers\TwilioController;
use App\Settings;

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
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            // 'user_type' => 'required|in:BUSINESSUSER,NORMAL',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $default_lang = @Settings::where('key','default_lang')->first()->value;
        $User = User::create([
            'first_name' => $data['first_name'],
             'last_name' => $data['last_name'],
            'email' => $data['email'],
            'mobile' => $data['country_code'].$data['phone_number'],
            'password' => bcrypt($data['password']),
            'payment_mode' => 'CASH',
            'user_type' => isset($data['user_type']) ? $data['user_type'] : 'NORMAL'
        ]);

        $user = User::find($User->id);
        $user->language = $default_lang;
        $user->save();

        Helper::site_registermail($User);

        return $User;

        // send welcome email here
    }

    
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('user.auth.register');
    }

    public function OTP(Request $request)   {   
      
   
        $messages = [                    
                    'mobile.unique' => 'You are already Registered',
                ];
        if($request->has('login_by'))
        {
            $this->validate($request, [
                'mobile' => 'required|unique:users|min:6',
                'login_by' => 'required',
                'accessToken' => 'required'
            ],$messages);  
        }
        else
        {

            $this->validate($request, [
                'mobile' => 'required|unique:users|min:6'
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
            }

            elseif(User::where('mobile',$data['mobile'])->first()){

                return response()->json([
                    'error' => trans('form.mobile_exist'),
                ], 422); 
            }

            $newotp = rand(1000,9999);
            $data['otp'] = $newotp;
            $data['phone'] = $data['mobile'];   
            $data['message'] = trans('unauthorized.email.your_otp').' '.$newotp;         
            $check =User::wheremobile($data['phoneonly'])->first();           

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
