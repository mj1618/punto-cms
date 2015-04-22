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

class AdminLogin extends Controller {

    function getLogin(){
        self::checkLaravelCookie();

        if(Auth::check()){
            if(Session::has('return_url')){
                $ret = Session::get('return_url');
                Session::remove('return_url');
                return Redirect::to($ret);
            }
            return Redirect::to('/admin');
        }

        return view('admin-ui::login', [ 'message' => Session::get('message') ,'actionUrl'=>'/admin-login' ]);
    }

    function postLogin(){
        $user = User::where('password','=',Input::get('password'))->where('username','=',Input::get('username'))->get()->first();

        if($user==null){
            Session::flash('message','Sorry, the details you entered could not be validated, please try again or contact support on +61 8 6102 5117');
            return Redirect::to('/admin-login');
        }

        Auth::login($user);

        $cookie = self::newCookie($user);

        if(Session::has('return_url')){
            $ret = Session::get('return_url');
            Session::remove('return_url');
            return Redirect::to($ret)->withCookie($cookie);
        }
        return Redirect::to('/admin')->withCookie($cookie);
    }

    function forwardAdmin(){

        if(Auth::check()){
            if(Auth::user()->hasRole('editor')){
                return Redirect::to('/admin/manage-pages');
            } else if(Auth::user()->hasRole('developer')){
                return Redirect::to('/admin/edit-pages');
            } else if(Auth::user()->hasRole('admin')){
                return Redirect::to('/admin/users');
            }
        }

        return Redirect::to('/admin/manage-pages');
    }

    public static function routes(){

        Route::get('/admin-login','AdminLogin@getLogin');
        Route::get('/admin','AdminLogin@forwardAdmin');

        Route::post('/admin-login','AdminLogin@postLogin');

        Route::get('/logout','Logout@getLogout');

    }

    private static function checkLaravelCookie()
    {
        $cv = Request::cookie('laravel-remember');
        \Debugbar::error("laravel cookie: $cv");
        $uc = UserCookie::where('cookie','=',$cv)->get()->first();
        if($uc!=null){
            $user = User::where('id','=',$uc->user_id)->get()->first();
            Auth::login($user);
        }
    }


    private static function newCookie($user)
    {

        $cv=str_random(32);
        $cookie = Cookie::forever('laravel-remember',$cv);
        $uc = new UserCookie();
        $uc->user_id=$user->id;
        $uc->cookie=$cv;
        $uc->save();
        return $cookie;
    }

}