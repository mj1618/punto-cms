<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Page;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\Template;
use App\AUI\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Log;
use MJ1618\AdminUI\Controller\Controller;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\ButtonItem;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\HeaderItem;
use MJ1618\AdminUI\Form\HeadingItem;
use MJ1618\AdminUI\Form\HiddenInput;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\NumberInput;
use MJ1618\AdminUI\Form\PasswordInput;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\Table;

class PageSummary extends Controller{

    function routes(){
        Route::get('/admin/manage-pages/{id1}/content','PageSummary@show');
    }

    function show(){

        $page = Page::find(Request::route('id1'));

        $views = [];

        $views[] = (new HeadingItem())
            ->label($page->name)
            ->views([(new ButtonItem())->target('_blank')->label("Open Page")->defaultValue($page->url)]);



        foreach($page->template()->first()->sections()->get() as $sec){

            $secViews = [];

            $header="$sec->name";

            $secButtons=[];
            $icon=null;

            foreach($page->posts()->where('section_id','=',$sec->id)->get() as $post){

                $buttons=[];
                $buttons[] = ["label"=>'Edit', "href"=>"/admin/manage-pages/".$page->id."/posts/".$post->id."/edit"];
                $buttons[] = ["label"=>'Delete', "href"=>"/admin/manage-pages/".$page->id."/posts/".$post->id."/delete"];

                if($sec->single===0){
                    $postName = $post->name;

                    if($post->hasContent("Title")){
                        $postName = $post->findContent("Title")->value;
                    } else if($post->hasContent("Name")){
                        $postName = $post->findContent("Name")->value;
                    }else if($post->hasContent("Description")){

                        $desc = $post->findContent("Description")->value;
                        if (strlen($desc) > 20)
                            $desc = substr($desc, 0, 17) . '...';
                        $postName = $desc;

                    }

                    $secViews[] = ViewUtils::box($postName,(new PageSummaryPostsForm())->postViews($post->id),null,null,true,$buttons,'fa-angle-double-right');
                } else {
                    $header = $post->section()->first()->name;//$post->name." - ".


                    $secViews[] = ViewUtils::plain((new PageSummaryPostsForm())->postViews($post->id));
                    $secButtons[] = ["label"=>'Edit', "href"=>"/admin/manage-pages/".$page->id."/posts/".$post->id."/edit"];
//                    $secButtons[] = ["label"=>'Delete', "href"=>"/admin/manage-pages/".$page->id."/posts/".$post->id."/delete"];
                    $icon = 'fa-angle-double-right';
                }
            }

            if($sec->single===0){
                $secButtons[] = ["label"=>'Add Post', "href"=>"/admin/manage-pages/".$page->id."/section/$sec->id/add-post"];
                $icon = 'fa-bars';
            } else if( count($secViews) === 0 ){
                $secButtons[] = ["label"=>'Create', "href"=>"/admin/manage-pages/".$page->id."/section/$sec->id/add-post"];
            }

            $views[] = ViewUtils::box($header,$secViews,null,null,false,$secButtons,$icon);

        }


//        dd($page->posts()->first()->name);

        return ViewUtils::page($views);
    }

}