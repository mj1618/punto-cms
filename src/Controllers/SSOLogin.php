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
use jyggen\Curl;
use MJ1618\AdminUI\Controller\Controller;
use Config;
use App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SSOLogin extends Controller {

    function getLogin(){
        return Redirect::to('https://sso.communitytogo.com.au/oauth/authorize?client_id='.Config::get('punto-cms.c2go-client-id').'&redirect_uri='.Config::get('punto-cms.c2go-redirect-uri').'&response_type=code&scope=view-email');
    }

    function oauthReturn(){
        $code = Input::get('code');

        if(!isset($code)){
            return View::make('ss/error/500');
        }
//        Log::info(date('H:i:s')." starting request");
        $client = App::make('guzzle-client');
        try{
            $response = $client->post('https://sso.communitytogo.com.au/oauth/access_token',[
                "body"=>[
                    "client_secret"=>Config::get('c2go-sso.client-secret'),
                    "code"=>$code,
                    "client_id"=>Config::get('c2go-sso.client-id'),
                    "redirect_uri"=>Config::get('c2go-sso.redirect-uri'),
                    "response_type"=>"code",
                    "scope"=>"view-email",
                    "grant_type"=>"authorization_code"
                ]]);
        } catch (ClientException $e) {
//            return $e->getResponse();
            Log::error($e->getResponse());
            return View::make('ss/error/500');
        }
//        Log::info(date('H:i:s')." finished request");
        $tok = json_decode($response->getBody())->access_token;

        try{
            $response2 = $client->post('https://sso.communitytogo.com.au/user/email',[
                "body"=>[
                    "access_token"=>$tok
                ]]);
        } catch (ClientException $e2) {
//            return $e2->getResponse();
            Log::error($e2->getResponse());
            return View::make('ss/error/500');
        }
        $email = $response2->getBody();

        if(!isset($email) || User::where('username','=',$email)->count() === 0){
            return View::make('punto-cms::401');
        } else {
            Auth::login(User::where('username','=',$email)->get()->first());

            if(Session::has('return_url')){
                return Redirect::to(Session::get('return_url'));
            } else {
                return $this->forwardAdmin();
            }
        }
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

        Route::get('/login','SSOLogin@getLogin');
        Route::get('/admin-login','SSOLogin@getLogin');
        Route::get('/oauth','SSOLogin@oauthReturn');

        Route::get('/admin','SSOLogin@forwardAdmin');

        Route::get('/logout','Logout@getLogout');

    }


}