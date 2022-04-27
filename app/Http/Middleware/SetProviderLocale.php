<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Settings;

class SetProviderLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard='provider')
    {
        $lang = 'en';
        $default_lang = @Settings::where('key','default_lang')->first()->value;
        if($default_lang){
            $lang = $default_lang;
        }
        if (Auth::guard($guard)->check()) {
            $user = @Auth::guard($guard)->user()->profile;
            if(@$user->language){
                $lang = @$user->language;
            }
        }
        \App::setLocale($lang);

        return $next($request);
    }
}