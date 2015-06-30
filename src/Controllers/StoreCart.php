<?php namespace App\AUI\Controllers;

use App\AUI\Model\StoreProduct;
use App\AUI\Model\StoreTypePrice;
use Illuminate\Support\Facades\Redirect;
use MJ1618\AdminUI\Controller\Controller;
use Route;
use Input;
use Cart;
use Session;
class StoreCart extends Controller {

    function post(){

//        dd(Input::all());

        $col = Cart::search(array('name' => Input::get('product'), 'options' => array('price'=>Input::get('price'),'select' => Input::get('select'))));

        if($col!==false){
            Cart::update($col[0], Cart::get($col[0])["qty"]+Input::get('quantity'));
        } else {
            Cart::add(uniqid(), Input::get('product'), Input::get('quantity'), StoreTypePrice::find(Input::get('price'))->price/100.0, Input::all() );
        }

        return Redirect::to(Input::get('return'));
    }

    function routes(){
        Route::post('/cart','StoreCart@post');
    }

}