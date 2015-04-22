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

class Logout extends Controller
{
    function getLogout()
    {
        $url = '/';

        if(Auth::check() && Auth::user()->hasRole('admin'))
            $url='/admin-login';


        Auth::logout();
        UserCookie::where('cookie', '=', Request::cookie('laravel-remember'))->delete();
        return Redirect::to($url)->withCookie(Cookie::forget('remember'))->withCookie(Cookie::forget('laravel-remember'));
    }
}