<?php namespace App\AUI\Controllers;

use App\AUI\Model\User;
use App\AUI\Model\UserCookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use MJ1618\AdminUI\Controller\Controller;
use Config;
class Logout extends Controller
{
    function getLogout(){
        return $this->get();
    }
    function get()
    {
        $url = '/';
        Auth::logout();
        UserCookie::where('cookie', '=', Request::cookie('laravel-remember'))->delete();

        if(Config::get('punto-cms.c2go-login')===true){
            $url = 'https://sso.communitytogo.com.au/logout';
        }

        return Redirect::to($url)->withCookie(Cookie::forget('remember'))->withCookie(Cookie::forget('laravel-remember'));
    }

    function routes(){
        Route::get('/logout','Logout@get');
    }
}