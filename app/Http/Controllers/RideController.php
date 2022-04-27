<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class RideController extends Controller
{
    protected $UserAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI)
    {
        $this->middleware('auth');
        $this->UserAPI = $UserAPI;
    }


    /**
     * Ride Confirmation.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm_ride(Request $request)
    {
        // return $request->all();

        $customMessages = [
            'reseiver_mobile.*.required' => 'The phone number field is required.',
            'reseiver_country_code.*.regex' => 'The country code field is invalid format.',
            'reseiver_country_code.*.required' => 'The country code field is required.'
        ];

        $this->validate($request, [
            'reseiver_mobile.*' => 'required',
            'reseiver_country_code.*' => 'required|regex:/^([+][0-9]*)$/',
        ],$customMessages);        

        $fare = $this->UserAPI->estimated_fare($request)->getData();
        $service = (new Resource\ServiceResource)->show($request->service_type);
        $cards = (new Resource\CardResource)->index();
        
        if(array_first($request->current_latitude) && array_first($request->current_longitude))
        {
            User::where('id',Auth::user()->id)->update([
                'latitude' => array_first($request->current_latitude),
                'longitude' => array_first($request->current_longitude)
            ]);
        }

        return view('user.ride.confirm_ride',compact('request','fare','service','cards'));
    }

    /**
     * Create Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_ride(Request $request)
    {
        return $this->UserAPI->send_request($request);
    }

    /**
     * Get Request Status Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function status()
    {
        return $this->UserAPI->request_status_check();
    }

    /**
     * Cancel Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel_ride(Request $request)
    {
        return $this->UserAPI->cancel_request($request);
    }

    /**
     * Rate Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request)
    {
        return $this->UserAPI->rate_provider($request);
    }
}
