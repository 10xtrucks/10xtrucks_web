<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Settings;

class RedirectIfAdmin
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = 'admin')
	{
		$lang = 'en';
        $default_lang = @Settings::where('key','default_lang')->first()->value;
        if($default_lang){
            $lang = $default_lang;
        }
		\App::setLocale($lang);
	    if (Auth::guard($guard)->check()) {
	        return redirect('admin/dashboard');
	    }

	    return $next($request);
	}
}