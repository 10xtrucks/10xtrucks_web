<?php

/*
|--------------------------------------------------------------------------
| User Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['user_locale']], function () {

Auth::routes();

});

Route::get('auth/facebook', 'Auth\SocialLoginController@redirectToFaceBook');
Route::get('auth/facebook/callback', 'Auth\SocialLoginController@handleFacebookCallback');
Route::get('auth/google', 'Auth\SocialLoginController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\SocialLoginController@handleGoogleCallback');
Route::post('account/kit', 'Auth\SocialLoginController@account_kit')->name('account.kit');
Route::post('/otp', 'Auth\RegisterController@OTP');
Route::post('/common/socket' , 'SocketController@commonSocket');

/*
|--------------------------------------------------------------------------
| Provider Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'provider','middleware' => ['provider_locale']], function () {

    Route::get('auth/facebook', 'Auth\SocialLoginController@providerToFaceBook');
    Route::get('auth/google', 'Auth\SocialLoginController@providerToGoogle');

    Route::get('/login', 'ProviderAuth\LoginController@showLoginForm');
    Route::post('/login', 'ProviderAuth\LoginController@login');
    Route::post('/logout', 'ProviderAuth\LoginController@logout');
    Route::post('/otp', 'ProviderAuth\RegisterController@OTP');

    Route::get('/register', 'ProviderAuth\RegisterController@showRegistrationForm');
    Route::post('/register', 'ProviderAuth\RegisterController@register');

    Route::post('/password/email', 'ProviderAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'ProviderAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'ProviderAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'ProviderAuth\ResetPasswordController@showResetForm');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

/*
|--------------------------------------------------------------------------
| Dispatcher Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'dispatcher'], function () {
    Route::get('/login', 'DispatcherAuth\LoginController@showLoginForm');
    Route::post('/login', 'DispatcherAuth\LoginController@login');
    Route::post('/logout', 'DispatcherAuth\LoginController@logout');

    Route::post('/password/email', 'DispatcherAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'DispatcherAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'DispatcherAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'DispatcherAuth\ResetPasswordController@showResetForm');
});

/*
|--------------------------------------------------------------------------
| Fleet Authentication Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'fleet'], function () {
    Route::get('/login', 'FleetAuth\LoginController@showLoginForm');
    Route::post('/login', 'FleetAuth\LoginController@login');
    Route::post('/logout', 'FleetAuth\LoginController@logout');

    Route::post('/password/email', 'FleetAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'FleetAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'FleetAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'FleetAuth\ResetPasswordController@showResetForm');
});

/*
|--------------------------------------------------------------------------
| Account Authentication Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'account'], function () {
    Route::get('/login', 'AccountAuth\LoginController@showLoginForm');
    Route::post('/login', 'AccountAuth\LoginController@login');
    Route::post('/logout', 'AccountAuth\LoginController@logout');

    Route::get('/register', 'AccountAuth\RegisterController@showRegistrationForm');
    Route::post('/register', 'AccountAuth\RegisterController@register');

    Route::post('/password/email', 'AccountAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'AccountAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'AccountAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'AccountAuth\ResetPasswordController@showResetForm');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['user_locale']], function () {

    Route::get('/', function () {
        return view('index');
    });

    Route::get('/ride', function () {
        return view('ride');
    });

    Route::get('/drive', function () {
        return view('drive');
    });

    Route::get('privacy', function () {
        $page = 'page_privacy';
        $title = trans('user.privacy_policy');
        return view('static', compact('page', 'title'));
    });

    Route::get('terms', function () {
        $page = 'terms';
        $title = trans('user.terms_cond');
        return view('static', compact('page', 'title'));
    });

    // help
    Route::get('/help', 'HomeController@help');
});


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['user_locale']], function () {

    Route::get('/dashboard', 'HomeController@index')->name('dashboard');

    // user profiles
    Route::get('/profile', 'HomeController@profile');
    Route::get('/edit/profile', 'HomeController@edit_profile');
    Route::post('/profile', 'HomeController@update_profile');

    // update password
    Route::get('/change/password', 'HomeController@change_password');
    Route::post('/change/password', 'HomeController@update_password');

    // ride
    Route::get('/confirm/ride', 'RideController@confirm_ride');
    Route::post('/create/ride', 'RideController@create_ride');
    Route::post('/cancel/ride', 'RideController@cancel_ride');
    Route::get('/onride', 'RideController@onride');
    Route::post('/payment', 'PaymentController@payment');
    Route::post('/rate', 'RideController@rate');

    // status check
    Route::get('/status', 'RideController@status');
    Route::get('/services' , 'HomeController@services');

    // trips
    Route::get('/trips', 'HomeController@trips');
    Route::get('/upcoming/trips', 'HomeController@upcoming_trips');

    //paystack

    Route::post('/paystack', 'PaymentController@redirectToGateway')->name('pay');

    Route::get('/payment/callback', 'PaymentController@handleGatewayCallback');

    Route::get('/paystack/notify', 'PaymentController@paystack_notify_get');

    Route::post('/paystack/notify', 'PaymentController@paystack_notify_post');

    // wallet
    Route::get('/wallet', 'HomeController@wallet');
    Route::post('/add/money', 'PaymentController@add_money');

    Route::any('app/walletpaySuccess', 'PaymentController@walletpaySuccessapi');
    Route::any('/walletpaySuccess/{id}', 'PaymentController@walletpaySuccess');
    Route::any('/paySuccess/{id}', 'PaymentController@paySuccess');
    Route::any('/walletpayCancel', 'PaymentController@walletpayCancel');
    Route::any('app/walletpayCancel', 'PaymentController@walletpayCancelapi');
    Route::any('/payCancel', 'PaymentController@payCancel');
    Route::any('/payNotify', 'PaymentController@payNotify');

    // payment
    Route::get('/payment', 'HomeController@payment');

    // card
    Route::resource('card', 'Resource\CardResource');

    // promotions
    Route::get('/promotions', 'HomeController@promotions_index')->name('promocodes.index');
    Route::post('/promotions', 'HomeController@promotions_store')->name('promocodes.store');

    

    //paypal wallet return
    Route::get('/payment/paypal/failure', 'PaypalController@paypal_failure');
    Route::get('/payment/paypal/paid/{id}/{req}', 'PaymentController@paypal_paid');

    Route::get('/payment/paypal/success/{id}/{req}', 'PaymentController@paypal_success_flow');

});
