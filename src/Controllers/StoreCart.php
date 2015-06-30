<?php namespace App\AUI\Controllers;

use App\AUI\Model\StoreProduct;
use App\AUI\Model\StoreSelectItem;
use App\AUI\Model\StoreTypePrice;
use Illuminate\Support\Facades\Redirect;
use MJ1618\AdminUI\Controller\Controller;
use Route;
use Input;
use Cart;
use Session;
class StoreCart extends Controller {

    function add(){

//        dd(Input::all());

        $col = Cart::search([
            'name' => Input::get('product'),
            'options' => [
                'price'=>Input::get('price'),
                'select' => Input::get('select')
            ]
        ]);



        $c = null;
        if($col!==false){
            Cart::update($col[0], Cart::get($col[0])["qty"]+Input::get('quantity'));
            $c = Cart::get($col[0]);
        } else {
            $id = uniqid();
            Cart::add($id, Input::get('product'), Input::get('quantity'), StoreTypePrice::find(Input::get('price'))->price/100.0, Input::all() );
            $c = Cart::get(Cart::search(['id'=>$id])[0]);
        }

        Session::flash('success','Successfully added '.StoreProduct::find($c["name"])->name.' - '.(isset($c["options"]["select"])?StoreSelectItem::find($c["options"]["select"])->name:'').' to your shopping cart.');
        if(Input::has('return'))
            return Redirect::to(Input::get('return'));
        else
            return Redirect::back();
    }

    function remove(){
        Cart::remove(Input::get('id'));
        return Redirect::back();
    }

    function update(){

        foreach(Input::all() as $key => $val){
            if(starts_with($key,'rowid-')){
                $rowid = substr($key,strlen('rowid-'));
                Cart::update($rowid,$val);
            }
        }

        if(Input::has('return'))
            return Redirect::to(Input::get('return'));
        else
            return Redirect::back();
    }

    function checkout(){

        Log::info('checkout: '.json_encode(Input::all())." ".json_encode(Cart::content()));

        try {
            \Stripe\Stripe::setApiKey(Config::get('punto-cms.stripe-secret'));
            $resp = \Stripe\Charge::create(array(
                "amount" => Cart::total(),
                "currency" => "aud",
                "source" => Input::get('token'), // obtained with Stripe.js
                "description" => "Website charge"
            ), array(
                "idempotency_key" => rand(100000000,1000000000),
            ));


            Log::info($resp);


            if(Input::has('return'))
                return Redirect::to(Input::get('return'));
            else
                return Redirect::back();

        } catch(\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            Log::info(json_encode($err));
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            Log::info(json_encode($err));
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            Log::info(json_encode($err));
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            Log::info(json_encode($err));
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            Log::info(json_encode($err));
        }
//        catch (Exception $e) {
//            // Something else happened, completely unrelated to Stripe
//        }

        Log::info('An error occurred in payment');
        Session::flash('error','Sorry, an error occurred, your payment could not be processed');


        return Redirect::back();
    }
    function routes(){
        Route::post('/cart','StoreCart@add');
        Route::post('/cart/update','StoreCart@update');
        Route::post('/cart/checkout','StoreCart@checkout');
        Route::get('/cart/remove','StoreCart@remove');
    }

}