<?php namespace App\AUI\Controllers;

use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreProductSubcategory;
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
class StoreSubcategorySort extends Controller {

    function get(){
        return ViewUtils::page(
            [
                ViewUtils::box('Sort Subcategories',
                    [
                        \View::make('punto-cms::sort')->with('actionUrl','/admin/store-categories/'.Request::route('id1').'/sort')->with('items',StoreSubcategory::where('store_category_id','=',Request::route('id1'))->orderBy('sort','ASC')->get())
                    ])
            ]);
    }


    function post(){

        foreach(json_decode(Input::get('json')) as $i){
            $c = StoreSubcategory::find($i->id);
            $c->sort=$i->sort;
            $c->save();
        }

        Session::flash('Success Message','Saved sort order');
        return \Redirect::to("/admin/store-categories/".Request::route('id1')."/view");
    }


    function routes(){
        Route::get('/admin/store-categories/{id1}/sort','StoreSubcategorySort@get');
        Route::post('/admin/store-categories/{id1}/sort','StoreSubcategorySort@post');
    }

}
