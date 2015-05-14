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
use Log;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SSOLogin extends Controller {

    function __construct(){
        App::singleton('guzzle-client',function(){
            return new Client([
                'defaults' => [
                    'config' => [
                        'stream_context' => [
                            'ssl' => [
                                'ciphers' => 'DHE-RSA-AES256-SHA:DHE-DSS-AES256-SHA:AES256-SHA:KRB5-DES-CBC3-MD5:'
                                    . 'KRB5-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:EDH-DSS-DES-CBC3-SHA:DES-CBC3-SHA:DES-CBC3-MD5:'
                                    . 'DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA:AES128-SHA:RC2-CBC-MD5:KRB5-RC4-MD5:KRB5-RC4-SHA:'
                                    . 'RC4-SHA:RC4-MD5:RC4-MD5:KRB5-DES-CBC-MD5:KRB5-DES-CBC-SHA:EDH-RSA-DES-CBC-SHA:'
                                    . 'EDH-DSS-DES-CBC-SHA:DES-CBC-SHA:DES-CBC-MD5:EXP-KRB5-RC2-CBC-MD5:EXP-KRB5-DES-CBC-MD5:'
                                    . 'EXP-KRB5-RC2-CBC-SHA:EXP-KRB5-DES-CBC-SHA:EXP-EDH-RSA-DES-CBC-SHA:EXP-EDH-DSS-DES-CBC-SHA:'
                                    . 'EXP-DES-CBC-SHA:EXP-RC2-CBC-MD5:EXP-RC2-CBC-MD5:EXP-KRB5-RC4-MD5:EXP-KRB5-RC4-SHA'
                                    . ':EXP-RC4-MD5:EXP-RC4-MD5',
                            ]
                        ],
//                        'curl' => [
//                            'CURLOPT_SSLVERSION' => 2,
//                            'CURLOPT_SSL_CIPHER_LIST' => 'TLSv1'
//                        ]
                    ]
                ]
            ]);
        });
    }

    function getLogin(){
        return Redirect::to('https://sso.communitytogo.com.au/oauth/authorize?client_id='.Config::get('punto-cms.c2go-client-id').'&redirect_uri='.Config::get('punto-cms.c2go-redirect-uri').'&response_type=code&scope=view-email');
    }

    function oauthReturn(){
        $code = Input::get('code');

        if(!isset($code)){
            return View::make('admin-ui::error/500');
        }
//        Log::info(date('H:i:s')." starting request");
        $client = App::make('guzzle-client');
        try{
            $response = $client->post('https://sso.communitytogo.com.au/oauth/access_token',[
                "body"=>[
                    "client_secret"=>Config::get('punto-cms.c2go-client-secret'),
                    "code"=>$code,
                    "client_id"=>Config::get('punto-cms.c2go-client-id'),
                    "redirect_uri"=>Config::get('punto-cms.c2go-redirect-uri'),
                    "response_type"=>"code",
                    "scope"=>"view-email",
                    "grant_type"=>"authorization_code"
                ]]);
        } catch (ClientException $e) {
//            return $e->getResponse();
            Log::error($e->getResponse());
            return View::make('admin-ui::error/500');
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
            return View::make('admin-ui::error/500');
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