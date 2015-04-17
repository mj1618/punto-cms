<?php namespace App\AUI\Middleware;

use App\AUI\Controllers\Logout;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class DeveloperAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(Auth::check()===false){
            Auth::logout();
            Session::put('return_url',Request::url());
            return redirect()->to('/admin-login');
        }

        if(Auth::user()->hasRole('developer')===false){
            return (new Logout())->getLogout();
        }

        return $next($request);
    }

}
