<?php namespace App\AUI\Controllers;

use App\AUI\Model\Content;
use App\AUI\Model\Feed;
use App\AUI\Model\Page;
use App\AUI\Model\Post;
use App\AUI\Model\User;
use App\AUI\Model\UserCookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use MJ1618\AdminUI\Controller\Controller;
use View;
use Log;
class FeedRoute extends Controller {

    var $useCache=false;

    function show(){

        $puntoFeed = Feed::orWhere(function($query){
            $query->where('url', '=', Request::path());
        })->orWhere(function($query){
            $query->where('url', '=', '/'.Request::path());
        })->orWhere(function($query){
            $query->where('url', '=', Request::url());
        })->get()->first();

        $feed = new \Feed;
        $feed->setTextLimit(10000);
        $feed->setView('punto-cms::rss');
        // cache the feed for 60 minutes (second parameter is optional)

        if($this->useCache)
            $feed->setCache(0, $puntoFeed->name);

        // check if there is cached feed and build new only if is not
        if (!$this->useCache || !$feed->isCached())
        {
            // creating rss feed with our most recent 20 posts
            $posts = Post::where('page_id','=',$puntoFeed->page_id)->where('section_id','=',$puntoFeed->section_id)->orderBy('created_at', 'desc')->get();

            // set your feed's title, description, link, pubdate and language
            $feed->title = $puntoFeed->name;
            $feed->description = '';
//            $feed->logo = 'http://yoursite.tld/logo.jpg';
            $feed->link = Request::url();
            $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
            if(isset($posts[0]))
                $feed->pubdate = $posts[0]->created_at;
            $feed->lang = 'en';
            $feed->setShortening(true); // true or false
//            $feed->setTextLimit(100); // maximum length of description text

            foreach ($posts as $post)
            {
                // set item's title, author, url, pubdate, description and content
                $desc = null;
                $title = null;
                if($post->hasContent("Description")){
                    $desc = $post->findContent("Description")->value;
                } else if($post->hasContent("Title")){
                    $desc = $post->findContent("Title")->value;
                } else if($post->hasContent("Name")){
                    $desc = $post->findContent("Name")->value;
                }

                if($post->hasContent("Title")){
                    $title = $post->findContent("Title")->value;
                } else if($post->hasContent("Name")){
                    $title = $post->findContent("Name")->value;
                }else if($post->hasContent("Description")){
                    $title = $post->findContent("Description")->value;
                }

                if($desc!==null)
                    $feed->add($title, null, null, $post->created_at, $desc, null);
            }

        } else {

        }

        // first param is the feed format
        // optional: second param is cache duration (value of 0 turns off caching)
        // optional: you can set custom cache key with 3rd param as string
        return $feed->render('rss');
    }

    public static function routes(){

        foreach(Feed::get() as $feed){
            if(isset($feed->url) && $feed->url!=='')
                Route::get($feed->url,'FeedRoute@show');
        }

    }


}