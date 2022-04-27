<?php

return array(

    'IOSUser'     => array(
        'environment' => env('IOS_USER_ENV', 'production'),
        'certificate' => app_path().'/apns/user/User.pem',
        'passPhrase'  => env('IOS_USER_PUSH_PASS', 'apple'),
        'service'     => 'apns'
    ),
    'IOSProvider' => array(
        'environment' => env('IOS_PROVIDER_ENV', 'production'),
        'certificate' => app_path().'/apns/provider/Provider.pem',
        'passPhrase'  => env('IOS_PROVIDER_PUSH_PASS', 'apple'),
        'service'     => 'apns'
    ),
    'AndroidUser' => array(
        'environment' => env('ANDROID_USER_ENV', 'production'),
        'apiKey'      => env('ANDROID_USER_PUSH_KEY', 'AAAAeGwkvMc:APA91bEbZKT8DtHXa5zZSx0XfjPAUP0N4wQVMlbCEoOeeps4_5ezFy7fy7Xu9iZ-GWaV3XZ1zgIq3BDMnhSRaCLCvCfvS8VelDsHyQzQsr9Gi1uyuUSllL8Nt_fFs6K1uvk12N8vATGH'),
        'service'     => 'gcm'
    ),
    'AndroidProvider' => array(
        'environment' => env('ANDROID_PROVIDER_ENV', 'production'),
        'apiKey'      => env('ANDROID_PROVIDER_PUSH_KEY', 'AAAAeGwkvMc:APA91bEbZKT8DtHXa5zZSx0XfjPAUP0N4wQVMlbCEoOeeps4_5ezFy7fy7Xu9iZ-GWaV3XZ1zgIq3BDMnhSRaCLCvCfvS8VelDsHyQzQsr9Gi1uyuUSllL8Nt_fFs6K1uvk12N8vATGH'),
        'service'     => 'gcm'
    )

);