<?php namespace App\AUI\Controllers;

use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Page;
use App\AUI\Model\Post;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreProductSubcategory;
use App\AUI\Model\StoreProduct;
use App\AUI\Model\StoreSubcategory;
use App\AUI\Model\Template;
use App\AUI\Model\User;
use Auth;
use File;
use Input;
use Password;
use Log;
use Request;
use Route;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\HiddenInput;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\NumberInput;
use MJ1618\AdminUI\Form\PasswordInput;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\Table;
use MJ1618\AdminUI\Controller\Controller;
use Session;
class PagesSort extends Controller {

    function get(){
        $pages=null;
        if(Auth::user()->userPages()->count()===0)
            $pages = Page::orderBy('sort','ASC')->orderBy('id','ASC')->get();
        else
            $pages = Page::whereIn('id',Auth::user()->userPages()->lists('page_id'))->orderBy('sort','ASC')->orderBy('id','ASC')->get();

        return ViewUtils::page(
            [
                ViewUtils::box('Sort Pages',
                    [
                        \View::make('punto-cms::sort')
                            ->with('actionUrl','/admin/sort-pages')
                            ->with('items',$pages)
                            ->with('key','name')
                    ])
            ]);
    }


    function post(){

        foreach(json_decode(Input::get('json')) as $i){
            $c = Page::find($i->id);
            $c->sort=$i->sort;
            $c->save();
        }

        Session::flash('Success Message','Saved sort order');
        return \Redirect::to("/admin/manage-pages");
    }


    function routes(){
        Route::get('/admin/sort-pages','PagesSort@get');
        Route::post('/admin/sort-pages','PagesSort@post');
    }

}
