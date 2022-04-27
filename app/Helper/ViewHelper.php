<?php

use App\PromocodeUsage;
use App\ServiceType;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
{
  $i = $j = $c = 0;
  for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
    if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
    ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) 
        $c = !$c;
  }
  return $c;
}
function currency($value = '')
{
	if($value == ""){
		return Setting::get('currency')."0.00";
	} else {
		return Setting::get('currency').$value;
	}
}

function distance($value = '')
{
    if($value == ""){
        return "0".Setting::get('distance', 'Km');
    }else{
        return $value.Setting::get('distance', 'Km');
    }
}

function img($img){
	if($img == ""){
		return asset('main/avatar.jpg');
	}else if (strpos($img, 'http') !== false) {
        return $img;
    }else{
		return asset('storage/'.$img);
	}
}

function image($img){
	if($img == ""){
		return asset('main/avatar.jpg');
	}else{
		return asset($img);
	}
}

function promo_used_count($promo_id)
{
	return PromocodeUsage::where('status','ADDED')->where('promocode_id',$promo_id)->count();
}

function curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close ($ch);
    return $return;
}

function get_all_service_types()
{
	return ServiceType::all();
}

function sendTwilioSMS($mobileno, $otp){
  //\Log::info("Twilio MObile".$mobileno. '---' .$otp);

    $status = '';
   
    $message = trans('unauthorized.email.verify_code')." ". $otp ."";
    $accountSid = Setting::get('twilio_accountsid');
    $authToken = Setting::get('twilio_token');
    $twilioNumber = Setting::get('twilio_mobile');
   
    $client = new Client($accountSid, $authToken);
    try {
      $client->messages->create(
          $mobileno,
          [
              "body" => $message,
              "from" => $twilioNumber
          ]);
      return $status = "success";
      
    }catch (TwilioException $e) {

      //\Log::info($e->getMessage());

      return $e->getMessage();
      // return response()->json(['error'=> $e->getMessage()]);
      /*Log::info(
          'Could not send SMS notification.' .
          ' Twilio replied with: ' . $e
          );*/
    }
  }

  function bulk_sms($mobileno, $otp){

    $message = 'Your Drop4U application otp is'.$otp;
    $senderid = 'Drop4U';
    $to =$mobileno;
    $token = 'epz00CHb8seNwhvQDYu2EogxcgTrNByUhi5ZuzfvdYdbwftmodws0ggia1GrMyaYZ5xRKjZqLWPFOeN2KXMC1tyZORkDgdrarKNd';
    $baseurl = 'https://smartsmssolutions.com/api/json.php?';

    $sms_array = array 
      (
      'sender' => $senderid,
      'to' => $to,
      'message' => $message,
      'type' => '0',
      'routing' => 4,
      'token' => $token
    );

    $params = http_build_query($sms_array);
    $ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL,$baseurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

    $response = curl_exec($ch);

    curl_close($ch);
    $response = json_decode($response, true);

    return $response; // response code

  }

  function nigeria_sms($mobileno, $otp){
//dd($mobileno);
    $a= 'oyinkus@yahoo.com'; //Note: urlencodemust be added forusernameand
    $b= 'venvyz-fuqhar-Jamta3'; // passwordas encryption code for security purpose.
    $c= 'Your Drop4U application otp is'.$otp;
    $d= 'Drop4U';
    $e= $mobileno;
    $data = array('username'=>$a, 'password'=>$b, 'sender'=>$d, 'message'=>$c, 'mobiles'=>$e, 'type' => 'text');
    $data = http_build_query($data);
    $api_url = 'https://portal.nigeriabulksms.com/api/';

    $ch = curl_init(); // Initialize a cURL connection
    curl_setopt($ch,CURLOPT_URL, $api_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);

    $result = json_decode($result);
    //dd($result);
    return $result;

  }

    function voicesms($mobileno, $otp){
 // \Log::info("Twilio MObile".$mobileno. '---' .$otp);

    $status = '';
   
    $message = trans('unauthorized.email.verify_code')." ". $otp ."";
    $accountSid = Setting::get('twilio_accountsid');
    $authToken = Setting::get('twilio_token');
    $twilioNumber = Setting::get('twilio_mobile');

    $client = new Client($accountSid, $authToken);
      try {
    $client->account->calls->create(  
        $mobileno,
        $twilioNumber,
        array(
            "url" => "http://demo.twilio.com/docs/voice.xml"
        )
    );
   
    // $client = new Client($accountSid, $authToken);
  
    //   $client->messages->create(
    //       $mobileno,
    //       [
    //           "body" => $message,
    //           "from" => $twilioNumber
    //       ]);
      return $status = "success";
      
    }catch (TwilioException $e) {

      //\Log::info($e->getMessage());

      return $e->getMessage();
      // return response()->json(['error'=> $e->getMessage()]);
      /*Log::info(
          'Could not send SMS notification.' .
          ' Twilio replied with: ' . $e
          );*/
    }

}