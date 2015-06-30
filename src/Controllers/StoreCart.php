<?php namespace App\AUI\Controllers;

use MJ1618\AdminUI\Controller\Controller;
use Route;
use Input;

class StoreCart extends Controller {

    function post(){

        dd(Input::all());


//        Cart::add(uniqid())
    }

    function routes(){
        Route::post('/cart','StoreCart@post');
    }

}