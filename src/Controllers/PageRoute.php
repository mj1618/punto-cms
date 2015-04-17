<?php namespace App\AUI\Controllers;

use App\AUI\Model\Content;
use App\AUI\Model\Page;
use App\AUI\Model\Post;
use App\AUI\Model\User;
use App\AUI\Model\UserCookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use MJ1618\AdminUI\Controller\Controller;
use View;
use Log;
class PageRoute extends Controller {

    function show(){

        $page = Page::orWhere(function($query){
            $query->where('url', '=', Request::path());
        })->orWhere(function($query){
            $query->where('url', '=', '/'.Request::path());
        })->orWhere(function($query){
            $query->where('url', '=', Request::url());
        })->get()->first();
        $ps = Post::where('page_id','=',$page->id)->get();

        $posts = [];
        foreach($page->template()->first()->sections() as $sec){
            $posts[$sec->name] = [];
        }
        foreach($ps as $p){
            $posts[$p->section()->first()->name][] = $p;
        }

        $fn = str_replace('.blade.php','',$page->template()->first()->filename);
        return View::make("aui/templates/".$fn)->with('posts',$posts);
    }

    public static function routes(){

        foreach(Page::get() as $page){
            Route::get($page->url,'PageRoute@show');
        }

    }


}