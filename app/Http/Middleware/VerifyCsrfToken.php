<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/provider/request/*',
        '/provider/profile/available',
        '/account/kit',
        '/payNotify',
        '/paySuccess',
        '/common/socket',
        '/payCancel',
        'walletpaySuccess/*',
        'walletpayCancel'
    ];
}
