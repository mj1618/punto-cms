<?php namespace App\AUI\Controllers;

use App\AUI\Model\Page;
use App\AUI\Model\User;
use App\AUI\Model\UserCookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use MJ1618\AdminUI\Controller\Controller;
use Request;
class PagePreview extends Controller {

    function show(){
        $page = Page::find(Request::route('id1'));
        return View::make('aui/pages/page-preview')->with('page',$page);
    }

    public static function routes(){
        Route::get('/admin/page/{id1}/preview',["as"=>'Page Preview', "uses"=>'PagePreview@show']);
    }


}