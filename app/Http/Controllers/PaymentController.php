<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SendPushNotification;

use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeInvalidRequestError;

use Log;
use Auth;
use Setting;
use Exception;
use Paystack;

use App\Helpers\Helper;

use App\Payfastpassbook;

use App\Card;
use App\User;
use App\UserRequests;
use App\UserRequestPayment;

use App\PaystackDetails;

use App\Paypal_Payment;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payee;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

class PaymentController extends Controller
{
    /**
    * payment for user.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct(Request $request){
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                env('PAYPAL_CLIENT_ID'),
                env('PAYPAL_SECRET')
            )
        );
        $this->apiContext->setConfig(
            array(
              'mode' => 'sandbox',
              'log.LogEnabled' => true,
              'log.FileName' => storage_path('logs/paypal.log'),
              'log.LogLevel' => 'DEBUG', 

          )
        );
    }


    public function payment(Request $request)
    {
        $this->validate($request, [
            'request_id' => 'required|exists:user_request_payments,request_id|exists:user_requests,id,paid,0,user_id,'.Auth::user()->id,
            // 'payment_mode' => 'required|in:CASH,CARD,PAYPAL',
            // 'card_id' => ['required_if:payment_mode,CARD','exists:cards,card_id,user_id,'.Auth::user()->id]
        ]);


        $UserRequest = UserRequests::find($request->request_id);

        if($UserRequest->payment_mode == 'PAYFAST'){

            $RequestPayment = UserRequestPayment::where('request_id',$request->request_id)->first(); 

            $amount = $RequestPayment->total * 100;

            try{

                $tnx_id = Helper::generate_booking_id();

                $model = new Payfastpassbook;
                $model->amount = $amount*100;
                $model->tnx_id = $tnx_id; 
                $model->user_id = Auth::user()->id;

                $model->request_id = $request->request_id;
                $cus_str = 'flow';
                $user = User::find($model->user_id);
                $model->save();
                if($request->ajax()){

                    $success = 'app/walletpaySuccess?m_payment_id='.$tnx_id;
                    $failure = 'app/walletpayCancel';

                }else{

                    $success = 'paySuccess/'.$tnx_id;
                    $failure = 'payCancel';

                }

                //$live_url = 'https://www.payfast.co.za/eng/process';

                return view('payfastwallet', compact('tnx_id','amount','user','success','failure','cus_str'));

            }catch(Exception $e){

                if($request->ajax()){
                    return response()->json(['error' => $e->getMessage()], 500);
                } else {
                    return back()->with('flash_error', $e->getMessage());
                }

            }

        }


        if($UserRequest->payment_mode=='PAYPAL'){

            $RequestPayment = UserRequestPayment::where('request_id',$request->request_id)->first(); 
            $amount = $RequestPayment->total;

            if($request->tips > 0){
                $RequestPayment->total = $RequestPayment->total;
                $RequestPayment->payable = $RequestPayment->payable;
                $RequestPayment->tips = $request->tips;
                $RequestPayment->save();
                $amount = $RequestPayment->total + $request->tips;

            }


            $request_id =$request->request_id;

            $Money=$amount;
            $PaymentAmount = $amount; 

            $payer = new Payer();
            $payer->setPaymentMethod("paypal");

            $amount = new Amount();
            $amount->setCurrency(env('PAYPAL_CURRENCY'))
            ->setTotal($PaymentAmount);

            $payee = new Payee();
            $payee->setEmail(env('PAYPAL_EMAIL'));

            $transaction = new Transaction();
            $transaction->setAmount($amount)

            ->setDescription("Payment description")
            ->setPayee($payee)
            ->setInvoiceNumber(uniqid());

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(url('/payment/paypal/success/'.\Auth::user()->id.'/'.$request_id))
            ->setCancelUrl(url("/payment/paypal/failure"));

            $payment = new Payment();
            $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

            $requests = clone $payment;


            try {
                $payment->create($this->apiContext);
            } catch (Exception $ex) {
                dd($ex);
                if($request->ajax()){
                    return response()->json(['error'=>$ex->getMessage()],422);
                }else{
                    return back()->with('flash_error', $ex->getMessage());
                }
            }

            $approvalUrl = $payment->getApprovalLink();

            $paypal_payment = new Paypal_Payment();
            $paypal_payment->reference=$request_id;
            $paypal_payment->amount=$Money;
            $paypal_payment->user_id=\Auth::user()->id;
            $paypal_payment->request_id=$request->request_id;

            if($request->ajax()){

                $paypal_payment->via='mobile';
                $paypal_payment->save();
                return response()->json(['url'=>$approvalUrl ,'status'=>'true']);
            }else{
                $paypal_payment->via='web';
                $paypal_payment->save();
                return redirect($approvalUrl);
            }
        }

        if($UserRequest->payment_mode == 'CARD') {

            $RequestPayment = UserRequestPayment::where('request_id',$request->request_id)->first(); 

            $StripeCharge = $RequestPayment->total * 100;

            try {

                $Card = Card::where('user_id',Auth::user()->id)->where('is_default',1)->first();

                Stripe::setApiKey(Setting::get('stripe_secret_key'));

                $Charge = Charge::create(array(
                    "amount" => $StripeCharge,
                    "currency" => "usd",
                    "customer" => Auth::user()->stripe_cust_id,
                    "card" => $Card->card_id,
                    "description" => "Payment Charge for ".Auth::user()->email,
                    "receipt_email" => Auth::user()->email
                ));

                $RequestPayment->payment_id = $Charge["id"];
                $RequestPayment->payment_mode = 'CARD';
                $RequestPayment->save();

                $UserRequest->paid = 1;
                $UserRequest->status = 'COMPLETED';
                $UserRequest->save();

                if($request->ajax()) {
                    return response()->json(['message' => trans('api.paid')]); 
                } else {
                    return redirect('dashboard')->with('flash_success','Paid');
                }

            } catch(StripeInvalidRequestError $e){
                if($request->ajax()){
                    return response()->json(['error' => $e->getMessage()], 500);
                } else {
                    return back()->with('flash_error', $e->getMessage());
                }
            } catch(Exception $e) {
                if($request->ajax()){
                    return response()->json(['error' => $e->getMessage()], 500);
                } else {
                    return back()->with('flash_error', $e->getMessage());
                }
            }
        }
    }


    /**
     * add wallet money for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_money(Request $request){


      // if($request->card_id=="PAYFAST"){
          $this->validate($request, [
                'amount' => 'required|integer',
            ]);

         try{    
              if($request->card_id=='PAYPAL'){

               $this->validate($request, [
                      'amount' => 'required|integer',
                  ]);

              $request_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);

              $Money=$request->amount;
              $PaymentAmount = $request->amount; 

              $payer = new Payer();
              $payer->setPaymentMethod("paypal");

              $amount = new Amount();
              $amount->setCurrency(env('PAYPAL_CURRENCY'))
              ->setTotal($PaymentAmount);

              $payee = new Payee();
              $payee->setEmail(env('PAYPAL_EMAIL'));

              $transaction = new Transaction();
              $transaction->setAmount($amount)

              ->setDescription("Payment description")
              ->setPayee($payee)
              ->setInvoiceNumber(uniqid());

              $redirectUrls = new RedirectUrls();
              $redirectUrls->setReturnUrl(url('/payment/paypal/paid/'.\Auth::user()->id.'/'.$request_id))
              ->setCancelUrl(url("/payment/paypal/failure"));

              $payment = new Payment();
              $payment->setIntent("sale")
              ->setPayer($payer)
              ->setRedirectUrls($redirectUrls)
              ->setTransactions(array($transaction));

              $requests = clone $payment;


              try {
                $payment->create($this->apiContext);
              } catch (Exception $ex) {
                \Log::info($ex->getMessage());
                return back()->with('flash_error', $ex->getMessage());
                exit(1);
              }

              $approvalUrl = $payment->getApprovalLink();

              $paypal_payment = new Paypal_Payment();
              $paypal_payment->reference=$request_id;
              $paypal_payment->amount=$Money;

              $paypal_payment->user_id=\Auth::user()->id;
             
               if($request->ajax()){

                   $paypal_payment->via='mobile';
                   $paypal_payment->save();

                   return response()->json(['url'=>$approvalUrl ,'status'=>'true']);

                }else{

                   $paypal_payment->via='web';
                   $paypal_payment->save();

                  return redirect($approvalUrl);
                }
                  

           }
        }
        catch(Exception $e)
        {
            if($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', $e->getMessage());
            }
        }


      // }

        $this->validate($request, [
                'amount' => 'required|integer',
                'card_id' => 'required|exists:cards,card_id,user_id,'.Auth::user()->id
            ]);

        try{
            
            $StripeWalletCharge = $request->amount * 100;

            Stripe::setApiKey(Setting::get('stripe_secret_key'));

            $Charge = Charge::create(array(
                  "amount" => $StripeWalletCharge,
                  "currency" => "usd",
                  "customer" => Auth::user()->stripe_cust_id,
                  "card" => $request->card_id,
                  "description" => "Adding Money for ".Auth::user()->email,
                  "receipt_email" => Auth::user()->email
                ));

            $update_user = User::find(Auth::user()->id);
            $update_user->wallet_balance += $request->amount;
            $update_user->save();

            Card::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
            Card::where('card_id',$request->card_id)->update(['is_default' => 1]);

            //sending push on adding wallet money
            (new SendPushNotification)->WalletMoney(Auth::user()->id,currency($request->amount));

            if($request->ajax()){
                return response()->json(['message' => currency($request->amount).trans('api.added_to_your_wallet'), 'user' => $update_user]); 
            } else {
                return redirect('wallet')->with('flash_success',currency($request->amount).' added to your wallet');
            }

        } catch(StripeInvalidRequestError $e) {
            if($request->ajax()){
                 return response()->json(['error' => $e->getMessage()], 500);
            }else{
                return back()->with('flash_error',$e->getMessage());
            }
        } catch(Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', $e->getMessage());
            }
        }
    }

    public function walletpaySuccess(Request $request,$id)
    {   
        $payfast_detail = Payfastpassbook::where('tnx_id',$id)->first();

        $update_user = User::find($payfast_detail->user_id);



        if($request->ajax()){
            return response()->json(['message' => currency($payfast_detail->amount).trans('api.added_to_your_wallet'), 'user' => $update_user]); 
        } else {
            return redirect('wallet')->with('flash_success',currency($payfast_detail->amount).' added to your wallet');
        }
    }

    public function walletpaySuccessapi(Request $request)
    {   
        $payfast_detail = Payfastpassbook::where('tnx_id',$request->m_payment_id)->first();

        $update_user = User::find($payfast_detail->user_id);
    
        return view('walletsuccess');

      
    }

    public function paySuccess(Request $request,$id)
    {

       if($request->ajax()) {
           return response()->json(['message' => trans('api.paid')]); 
        } else {
            return redirect('dashboard')->with('flash_success','Paid');
        }
    }

    /**
     * After Payment Cancel
     */
    public function walletpayCancel(Request $request)
    {
        if($request->ajax()) {
            return response()->json(['message' => trans('api.paid_fail')]); 
        } else {
            return redirect('wallet')->with('flash_error','Payment Fail');
        }
    }

    public function walletpayCancelapi(Request $request)
    {
       return view('walletfailure');
    }

    public function payCancel(Request $request)
    {
      if($request->ajax()) {
         return response()->json(['message' => trans('api.paid_fail')]); 
      } else {
          return redirect('dashboard')->with('flash_error','Payment Fail');
      }
    }

    public function payNotify(Request $request)
    {
   //   \Log::info($request->all());
        
      if($_POST['custom_str1'] == 'wallet')
      {
        $request_id = $_POST['m_payment_id'];
        $model = Payfastpassbook::where('tnx_id', $request_id)->first();
        if($model){

          $update_user = User::find($model->user_id);
          $update_user->wallet_balance += $model->amount;
          $update_user->save();

          (new SendPushNotification)->WalletMoney($model->user_id,currency($model->amount));

        }
        
      }
      else
      {
        $request_id = $_POST['m_payment_id'];
        $model = Payfastpassbook::where('tnx_id', $request_id)->first();
        if($model){


            $request_id = $model->request_id;

            $UserRequest = UserRequests::find($model->request_id);
            $RequestPayment = UserRequestPayment::where('request_id',$request_id)->first(); 

            $RequestPayment->payment_id = $_POST['pf_payment_id'];
            $RequestPayment->payment_mode = 'PAYFAST';
            $RequestPayment->save();

            $UserRequest->paid = 1;
            $UserRequest->status = 'COMPLETED';
            $UserRequest->save();


        }
        
      }
      
    }

    public function redirectToGateway(Request $request)

    {  //dd($request);
         $det = new PaystackDetails();
         $det->reference = $request->reference;
         $det->request_id = $request->request_id;
         $det->user_id = Auth::user()->id;
         $det->save();

         $RequestPayment = UserRequestPayment::where('request_id',$request->request_id)->first();
         if($request->tips > 0){
          $RequestPayment->total = $RequestPayment->total + $request->tips;
          $RequestPayment->payable = $RequestPayment->payable + $request->tips;
          $RequestPayment->tips = $request->tips;
          $RequestPayment->save();
         }
         
      
        return Paystack::getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback(Request $request)
    {
        $paymentDetails = Paystack::getPaymentData();

       //dd($paymentDetails);

        if($paymentDetails){

          $paystackdetail = PaystackDetails::where('reference',$paymentDetails['data']['reference'])->firstOrFail();
       
          $paystackdetail->paystack_id = $paymentDetails['data']['id'];
          $paystackdetail->amount =  $paymentDetails['data']['amount'];
          $paystackdetail->currency =  $paymentDetails['data']['currency'];
          $paystackdetail->transaction_date =  $paymentDetails['data']['transaction_date'];
          $paystackdetail->status =  $paymentDetails['data']['status'];
          $paystackdetail->reference = $paymentDetails['data']['reference'];
          $paystackdetail->domain =  $paymentDetails['data']['domain'];
          $paystackdetail->gateway_response =  $paymentDetails['data']['gateway_response'];
          $paystackdetail->message =  $paymentDetails['data']['message'];
          $paystackdetail->channel =  $paymentDetails['data']['channel'];
          $paystackdetail->save();
                  
            if($paymentDetails['data']['status']=='success'){
           
              if($paystackdetail->request_id!=0){
                    $UserRequest = UserRequests::find($paystackdetail->request_id);

                    $RequestPayment = UserRequestPayment::where('request_id',$UserRequest->id)->first(); 

                    $amount = $paystackdetail->amount/100;

                   

                    $RequestPayment->payment_id = $paymentDetails['data']['id'];
                    $RequestPayment->payment_mode = 'CARD';
                    $RequestPayment->save();

                    $UserRequest->paid = 1;
                    $UserRequest->status = 'COMPLETED';
                    $UserRequest->save();

                    if($request->ajax()){
                       return response()->json(['message' => trans('api.paid')]); 
                    }else{
                        return redirect('dashboard')->with('flash_success','Paid');
                    }

                //for create the transaction
            
                 
              }else{

                      $amount = $paystackdetail->amount/100;

                      $update_user = User::find(Auth::user()->id);
                      $update_user->wallet_balance += $amount;
                      $update_user->save();

               
                      //sending push on adding wallet money
                      (new SendPushNotification)->WalletMoney(Auth::user()->id,currency($amount));

                      if($request->ajax()){
                         return response()->json(['message' => currency($amount).trans('api.added_to_your_wallet'), 'user' => $update_user]); 
                      }else{
                          return redirect('wallet')->with('flash_success',currency($amount).' added to your wallet');
                      }




              }

            }else{

                  return redirect('dashboard')->with('flash_error', 'Payment Failed');

            }

         

        }
        
    }

        public function providerhandleGatewayCallback(Request $request)
    {
        $paymentDetails = Paystack::getPaymentData();


        if($paymentDetails){

          $paystackdetail = PaystackDetail::where('reference',$paymentDetails['data']['reference'])->firstOrFail();
       
          $paystackdetail->paystack_id = $paymentDetails['data']['id'];
          $paystackdetail->amount =  $paymentDetails['data']['amount'];
          $paystackdetail->currency =  $paymentDetails['data']['currency'];
          $paystackdetail->transaction_date =  $paymentDetails['data']['transaction_date'];
          $paystackdetail->status =  $paymentDetails['data']['status'];
          $paystackdetail->reference = $paymentDetails['data']['reference'];
          $paystackdetail->domain =  $paymentDetails['data']['domain'];
          $paystackdetail->gateway_response =  $paymentDetails['data']['gateway_response'];
          $paystackdetail->message =  $paymentDetails['data']['message'];
          $paystackdetail->channel =  $paymentDetails['data']['channel'];
          $paystackdetail->save();
                  
            if($paymentDetails['data']['status']=='success'){
           
              
                  $amount = $paystackdetail->amount/100;
                    
                  $wallet_balance=Auth::user()->wallet_balance+$amount;

                  //\Log::info("After Adding Wallet amount---".$wallet_balance);

                  $transaction_alias = mt_rand('11111','99999');
                  $ipdata=array();
                  $ipdata['transaction_id']=$transaction_alias;
                  $ipdata['transaction_alias']=$transaction_alias;
                  $ipdata['transaction_desc']="Wallet Recharge";
                  $ipdata['transaction_type']=4;        
                  $ipdata['type']='C';
                  $ipdata['amount']=$amount;
                  $this->createAdminWallet($ipdata);

                  
                  $ipdata=array();
                  $ipdata['transaction_id']=$transaction_alias;
                  $ipdata['transaction_alias']=$transaction_alias;
                  $ipdata['transaction_desc']="Wallet Recharge";
                  $ipdata['id']=Auth::user()->id;        
                  $ipdata['type']='c';
                  $ipdata['amount']=$amount;
                  $ipdata['payment_mode']='CARD';
                  $this->createProviderWallet($ipdata);

                  (new SendPushNotification)->ProviderWalletMoney(Auth::user()->id,currency($amount));

                if($request->ajax()){
                    return response()->json(['success' => currency($amount)." ".trans('api.added_to_your_wallet'), 'message' => currency($amount)." ".'added to your wallet', 'balance' => $wallet_balance]); 
                } else {
                    return redirect('/provider/wallet_transation')->with('flash_success',currency($amount).'added to your wallet');
                }




  

            }else{

                  return redirect('dashboard')->with('flash_error', 'Payment Failed');

            }

         

        }
        
    }



      public function paystack_status(Request $request)

    {   

       if($request->reference != ''){
         $det = new PaystackDetails();
         $det->reference = $request->reference;
         $det->request_id = $request->request_id;
         

         $det->user_id = Auth::user()->id;
         $det->save();


         $UserRequest = UserRequests::find($request->request_id);
         if($UserRequest){
                $RequestPayment = UserRequestPayment::where('request_id',$UserRequest->id)->first(); 

                

                $RequestPayment->payment_id = $request->reference;
                $RequestPayment->payment_mode = 'CARD';
                if($request->tips > 0){
                $RequestPayment->tips = $request->tips;
                $RequestPayment->total = $RequestPayment->total + $request->tips;
                $RequestPayment->payable = $RequestPayment->payable + $request->tips;
                }
                
                $RequestPayment->save();

                $UserRequest->paid = 1;
                $UserRequest->status = 'COMPLETED';
                $UserRequest->save();

    
                if($request->ajax()) {
                   return response()->json(['message' => trans('api.paid')]); 
                } else {
                    return redirect('dashboard')->with('flash_success', trans('api.paid'));
                }

            }

       }
       
    }



    public function paystack__wallet_status(Request $request)

    {   

       if($request->reference != ''){
             $det = new PaystackDetails();
             $det->reference = $request->reference;
             $det->request_id = $request->request_id;
             $det->amount  = $request->amount;
             $det->user_id = Auth::user()->id;
             $det->save();

             $amount =$request->amount;
           

              $update_user = User::find(Auth::user()->id);
              $update_user->wallet_balance += $amount;
              $update_user->save();

       
              //sending push on adding wallet money
              (new SendPushNotification)->WalletMoney(Auth::user()->id,currency($amount));

              if($request->ajax()){
                 return response()->json(['message' => currency($amount).trans('api.added_to_your_wallet'), 'user' => $update_user]); 
              }else{
                  return redirect('wallet')->with('flash_success',currency($amount).' added to your wallet');
              }



       }else{

              if($request->ajax()){
                      return response()->json(['message' => 'Error']); 
                  } else {
                      return redirect('wallet');
                  }
       }
       
    }


    public function provider_paystack__wallet_status(Request $request)

    {   

       if($request->reference != ''){
             $det = new PaystackDetails();
             $det->reference = $request->reference;
             $det->request_id = $request->request_id;
             $det->amount  = $request->amount;
             $det->user_id = Auth::user()->id;
             $det->save();

             $amount =$request->amount;

             $wallet_balance=Auth::user()->wallet_balance+$amount;

                  //\Log::info("After Adding Wallet amount---".$wallet_balance);

                  $transaction_alias = mt_rand('11111','99999');
                  $ipdata=array();
                  $ipdata['transaction_id']=$transaction_alias;
                  $ipdata['transaction_alias']=$transaction_alias;
                  $ipdata['transaction_desc']="Wallet Recharge";
                  $ipdata['transaction_type']=4;        
                  $ipdata['type']='C';
                  $ipdata['amount']=$amount;
                  $this->createAdminWallet($ipdata);

                  
                  $ipdata=array();
                  $ipdata['transaction_id']=$transaction_alias;
                  $ipdata['transaction_alias']=$transaction_alias;
                  $ipdata['transaction_desc']="Wallet Recharge";
                  $ipdata['id']=Auth::user()->id;        
                  $ipdata['type']='c';
                  $ipdata['amount']=$amount;
                  $ipdata['payment_mode']='CARD';
                  $this->createProviderWallet($ipdata);

                  (new SendPushNotification)->ProviderWalletMoney(Auth::user()->id,currency($amount));

                if($request->ajax()){
                    return response()->json(['success' => currency($amount)." ".trans('api.added_to_your_wallet'), 'message' => currency($amount)." ".'added to your wallet', 'balance' => $wallet_balance]); 
                } else {
                    return redirect('/provider/wallet_transation')->with('flash_success',currency($amount).'added to your wallet');
                }
           

              



       }else{

              if($request->ajax()){
                      return response()->json(['message' => 'Error']); 
                  } else {
                      return redirect('/provider/wallet_transation');
                  }
       }
       
    }

    public function add_acc_no(Request $request){

      try {

        $url = 'https://api.paystack.co/bank/resolve?account_number='.$request->account_number.'&bank_code='.$request->bank_code;

        $sk = env('PAYSTACK_SECRET_KEY');


        $res = acc_verify($url,$sk);

        if($res->status==true){

          $provider = Provider::where('id',\Auth::user()->id)->first();
          $provider->account_number=$res->data->account_number;
          $provider->bank_code=$request->bank_code;
          $provider->account_name=$res->data->account_name;
          $provider->save();

         if($request->ajax()){                   
              return response()->json(['message' => $res->message]); 
          }else{
              return back()->with('flash_success',$res->message);
          }


        }else{

          if($request->ajax()){                   
              return response()->json(['message' => $res->message]); 
          }else{
              return back()->with('flash_error',$res->message);
          }


        }


        //dd($res);          

        
      } catch (Exception $e) {

         if($request->ajax()){                   
              return response()->json(['message' => 'Something Went Wrong']); 
          }else{
              return back()->with('flash_error','Something Went Wrong');
          }

        
      }

    }

    public function send_money(Request $request, $id)
    {

        try {

            $Requests = WalletRequests::where('id', $id)->first();

            if ($Requests->request_from == 'provider') {
                $provider = Provider::find($Requests->from_id);
                $stripe_acc_id = $provider->account_name;
                $email = $provider->email;
            } else {
                $fleet = Fleet::find($Requests->from_id);
                $stripe_acc_id = $fleet->account_name;
                $email = $fleet->email;
            }

            if (empty($stripe_acc_id)) {
                throw new Exception(trans('admin.payment_msgs.account_not_found'));
            }

            $amount = $Requests->amount*100;
            
            $url = 'https://api.paystack.co/transfer/finalize_transfer';            
            $sk = env('PAYSTACK_SECRET_KEY');
            
            $postfield = array('transfer_code' =>$request->transfer_code,'otp'=>$request->otp);


            $paystack_pay = acc_charge($url,$sk,$postfield);

          //dd($paystack_pay);

            if($paystack_pay->status==true){

                 (new TripController)->settlements($id);

                 $response = array();
                 $response['success'] = trans('admin.payment_msgs.amount_send');

            }else{

              $response['error']='Payment Failed';

            }




           /* Stripe::setApiKey(Setting::get('stripe_secret_key'));

            $tranfer = \Stripe\Transfer::create(array(
                "amount" => $StripeCharge,
                "currency" => "usd",
                "destination" => $stripe_acc_id,
                "description" => "Payment Settlement for " . $email,
            ));*/

            //create the settlement transactions
           
            

        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

        public function send_money_paystack(Request $request, $id)
    {

        try {

            $Requests = WalletRequests::where('id', $id)->first();

            if ($Requests->request_from == 'provider') {
                $provider = Provider::find($Requests->from_id);
                $stripe_acc_id = $provider->account_name;
                $email = $provider->email;
            } else {
                $fleet = Fleet::find($Requests->from_id);
                $stripe_acc_id = $fleet->account_name;
                $email = $fleet->email;
            }

            if (empty($stripe_acc_id)) {
                throw new Exception(trans('admin.payment_msgs.account_not_found'));
            }

            $amount = $Requests->amount*100;
            
            $url = 'https://api.paystack.co/transferrecipient';
            $sk = env('PAYSTACK_SECRET_KEY');


            $postfield = array('type' =>'nuban','name'=>$provider->account_name,'description'=>'Admin Settlement','account_number'=>$provider->account_number,'bank_code'=>'011','currency'=>'NGN' );

            if($provider->account_ref==''){

              $paystack_transfer = acc_charge($url,$sk,$postfield);

              if($paystack_transfer->status==true){

                   $provider->account_ref = $paystack_transfer->data->recipient_code;
                   $provider->save();

                   $url = 'https://api.paystack.co/transfer';

                   $postfield=array('source' =>'balance','reason'=>'Admin Settle to Provider','amount'=>$amount,'recipient'=>$provider->account_ref );


                   $paystack_pay = acc_charge($url,$sk,$postfield);

                   return $paystack_pay;

                //   dd($paystack_pay);





              }else{

                if($request->ajax()){                   
                    return response()->json(['message' => $paystack_transfer->message]); 
                }else{
                    return back()->with('flash_error',$paystack_transfer->message);
                }

              }

            }else{

                  $url = 'https://api.paystack.co/transfer';

                  $postfield=array('source' =>'balance','reason'=>'Admin Settle to Provider','amount'=>$amount,'recipient'=>$provider->account_ref );


                 $paystack_pay = acc_charge($url,$sk,$postfield);

                 return $paystack_pay;

                



            }
            
            


        } catch (Exception $e) {
             return $e->getMessage();
        }

      
    }

    public function paystack_notify_post(Request $request){

      /*\Log::info($request->all());
      \Log::info("post");*/

    }

    public function paystack_notify_get(Request $request){

      /*\Log::info($request->all());
      \Log::info("get");*/

    }


     public function paypal_paid(Request $request,$id,$req){
      try {
        
              $payment = Payment::get($request->paymentId, $this->apiContext);
             
              $execution = new PaymentExecution();
              $execution->setPayerId($request->PayerID);
           
              //Execute the payment
              $result = $payment->execute($execution, $this->apiContext);
              $payment = $result->gettransactions();
              $paypal_amount = $payment[0]->amount->total;

              if($result->getState() == 'approved') {
               
               $Paypal_Payment = Paypal_Payment::where('reference',$req)->first();

                 if($Paypal_Payment->paid==0){

                      $update_user = User::find($id);
                      $update_user->wallet_balance += $paypal_amount;
                      $update_user->save();
                      (new SendPushNotification)->WalletMoney($id,currency($paypal_amount));

                                    
                       $Paypal_Payment->paid=1;
                       $Paypal_Payment->save();

                      if($Paypal_Payment->via=='mobile'){
                         return response()->json(['message' => currency($paypal_amount).trans('api.added_to_your_wallet'), 'user' => $update_user]); 
                      }else{
                          return redirect('wallet')->with('flash_success',currency($paypal_amount).' added to your wallet');
                      }

                 }else{

                    (new SendPushNotification)->WalletMoney($id,currency($paypal_amount));

                 }


              }else{

                return redirect('wallet')->with('flash_error', "Sorry something went wrong, Please try again.");
              }
      }catch (Exception $e) {
       // dd($e);
         \Log::info($e->getMessage());
         return redirect('wallet')->with('flash_error', $e->getMessage());
      }  

    }

    public function paypal_failure(Request $request){

      try {
          
          return redirect('dashboard')->with('flash_success', "Payment Error Occured.");  
          
      } catch (Exception $ex) {
         return redirect('dashboard')->with('flash_error', $ex->getMessage());
      }  

    }


    public function paypal_success_flow(Request $request,$id,$req){

              $payment = Payment::get($request->paymentId, $this->apiContext);
             
              $execution = new PaymentExecution();
              $execution->setPayerId($request->PayerID);
           
              //Execute the payment
              $result = $payment->execute($execution, $this->apiContext);
              $payment = $result->gettransactions();
              $paypal_amount = $payment[0]->amount->total;
              \Log::info('kavi');
              \Log::info($result->getState());
              if($result->getState() == 'approved') {

                $Paypal_Payment = Paypal_Payment::where('reference',$req)->first();

                 // if($Paypal_Payment->amount==$paypal_amount){

                      $UserRequest = UserRequests::find($req);
                      $RequestPayment = UserRequestPayment::where('request_id',$req)->first(); 
                      $RequestPayment->payment_id = $request->paymentId;
                      $RequestPayment->payment_mode = 'PAYPAL';
                      $RequestPayment->save();

                      $UserRequest->paid = 1;
                      $UserRequest->status ='COMPLETED';
                      $UserRequest->save();

                     

                     $Paypal_Payment->request_id=$req;
                     $Paypal_Payment->paid=1;
                     $Paypal_Payment->save();

                     if($Paypal_Payment->via=='mobile'){
                         return response()->json(['message' => trans('api.paid')]); 
                      }else{
                          return redirect('dashboard')->with('flash_success','Paid');
                      }



                 // }else{

                 //   return redirect('dashboard')->with('flash_error','Payment Failed');

                 // }

              }else{
                  return redirect('dashboard')->with('flash_error','Payment Failed');
              }

    }
}
