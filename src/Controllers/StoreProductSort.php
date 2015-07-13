<?php namespace App\AUI\Controllers;

use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
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
class StoreProductSort extends Controller {

    function get(){
        return ViewUtils::page(
            [
                ViewUtils::box('Sort Products',
                    [
                        \View::make('punto-cms::sort')->with('actionUrl','/admin/store-products/sort')->with('items',StoreProduct::orderBy('sort','ASC')->get())
                    ])
            ]);
    }


    function post(){

        foreach(json_decode(Input::get('json')) as $i){
            $c = StoreProduct::find($i->id);
            $c->sort=$i->sort;
            $c->save();
        }

        Session::flash('Success Message','Saved sort order');
        return \Redirect::to("/admin/store-products");
    }


    function routes(){
        Route::get('/admin/store-products/sort','StoreProductSort@get');
        Route::post('/admin/store-products/sort','StoreProductSort@post');
    }

}
