<?php

return [
  'gcm' => [
      'priority' => 'high',
      'dry_run' => false,
      'apiKey' => env('ANDROID_USER_PUSH_KEY', 'AAAAeGwkvMc:APA91bEbZKT8DtHXa5zZSx0XfjPAUP0N4wQVMlbCEoOeeps4_5ezFy7fy7Xu9iZ-GWaV3XZ1zgIq3BDMnhSRaCLCvCfvS8VelDsHyQzQsr9Gi1uyuUSllL8Nt_fFs6K1uvk12N8vATGH'),
  ],
  'fcm' => [
        'priority' => 'high',
        'dry_run' => false,
        'apiKey' => env('ANDROID_PROVIDER_PUSH_KEY', 'AAAAeGwkvMc:APA91bEbZKT8DtHXa5zZSx0XfjPAUP0N4wQVMlbCEoOeeps4_5ezFy7fy7Xu9iZ-GWaV3XZ1zgIq3BDMnhSRaCLCvCfvS8VelDsHyQzQsr9Gi1uyuUSllL8Nt_fFs6K1uvk12N8vATGH'),
  ],
  'apn' => [
      
  ]
];
