<?php namespace App\AUI\Controllers;

use App\AUI\Model\StoreOrder;
use App\AUI\Model\StoreOrderProduct;
use App\AUI\Model\StoreProduct;
use App\AUI\Model\StoreSelectItem;
use App\AUI\Model\StoreTypePrice;
use Redirect;
use MJ1618\AdminUI\Controller\Controller;
use Route;
use Input;
use Cart;
use Session;
use Log;
use Config;

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
            Cart::add($id, Input::get('product'), Input::get('quantity'), (StoreTypePrice::find(Input::get('price'))?StoreTypePrice::find(Input::get('price'))->price:StoreProduct::find(Input::get('product'))->price), Input::all() );
            $c = Cart::get(Cart::search(['id'=>$id])[0]);
        }

        Session::flash('success','Successfully added '.StoreProduct::find($c["name"])->name.' - '.(StoreSelectItem::find($c["options"]["select"])!==null?StoreSelectItem::find($c["options"]["select"])->name:'').' to your shopping cart.');
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

//    function checkout()
//    {
//
//        Log::info('checkout: ' . json_encode(Input::all()) . " " . json_encode(Cart::content()));
//
//        Session::put('checkout',Input::all());
//
//        if(Input::has('return'))
//            return Redirect::to(Input::get('return'));
//        else
//            return Config::get('punto-cms.payment-url');
//    }
//    function payment(){
//
//        if(Session::has('checkout')===false){
//            return Redirect::to(Config::get('punto-cms.checkout-url'));
//        }
//
//        $shipping = Session::has('shipping')?Session::get('shipping'):0;
//
//        try {
//            \Stripe\Stripe::setApiKey(Config::get('punto-cms.stripe-secret'));
//            $resp = \Stripe\Charge::create(array(
//                "amount" => 100*(Cart::instance('main')->total()+$shipping),
//                "currency" => "aud",
//                "source" => Input::get('token'), // obtained with Stripe.js
//                "description" => "Website charge"
//            ), array(
//                "idempotency_key" => Input::get('idempotency'),
//            ));
//
//
//            Log::info('success stripe payment for amount '.(100*(Cart::instance('main')->total()+$shipping)).': '.$resp);
//
//
//            return $this->complete();
//
//        } catch(\Stripe\Error\Card $e) {
//            $body = $e->getJsonBody();
//            $err  = $body['error'];
//            Log::info(json_encode($err));
//        } catch (\Stripe\Error\InvalidRequest $e) {
//            $body = $e->getJsonBody();
//            $err  = $body['error'];
//            Log::info(json_encode($err));
//        } catch (\Stripe\Error\Authentication $e) {
//            $body = $e->getJsonBody();
//            $err  = $body['error'];
//            Log::info(json_encode($err));
//        } catch (\Stripe\Error\ApiConnection $e) {
//            $body = $e->getJsonBody();
//            $err  = $body['error'];
//            Log::info(json_encode($err));
//        } catch (\Stripe\Error\Base $e) {
//            $body = $e->getJsonBody();
//            $err  = $body['error'];
//            Log::info(json_encode($err));
//        }
////        catch (Exception $e) {
////            // Something else happened, completely unrelated to Stripe
////        }
//
//
//        return $this->cancel();
//    }

    function paypal(){

        $shipping = Session::has('shipping')?Session::get('shipping'):0;

        Session::put('shipping-review',$shipping);
        Cart::instance('review')->destroy();
        foreach(Cart::instance('main')->content()->toArray() as $key => $val){
            Cart::instance('review')->add($val);
        }

        //Our request parameters
        $requestParams = array(
            'RETURNURL' => Config::get('punto-cms.paypal-return'),
            'CANCELURL' => Config::get('punto-cms.paypal-cancel')
        );


        if(Session::get('method')==='cc'){
            Log::info('cc method');
            $pageParams = [
                'LANDINGPAGE'=>'Billing'
            ];
        } else {
            $pageParams = [
                'LANDINGPAGE'=>'Login'
            ];
        }

        $orderParams = [
            'PAYMENTREQUEST_0_AMT' => (Cart::instance('review')->total()+$shipping),
            'PAYMENTREQUEST_0_SHIPPINGAMT' => $shipping,
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'AUD',
            'PAYMENTREQUEST_0_ITEMAMT' => Cart::instance('review')->total()
        ];

        $items = [];
        $i = 0;
        foreach(Cart::instance('main')->content() as $item){

            $items = $items + [
                    'L_PAYMENTREQUEST_0_NAME'.$i => StoreProduct::find($item["name"])->name." - ".(StoreTypePrice::find($item["options"]["price"])!=null?StoreSelectItem::find($item["options"]["select"])->name:''),
                    'L_PAYMENTREQUEST_0_DESC'.$i => (StoreTypePrice::find($item["options"]["price"])!=null)?StoreTypePrice::find($item["options"]["price"])->name:'$'.StoreProduct::find($item["name"])->price,
                    'L_PAYMENTREQUEST_0_AMT'.$i => $item["price"],
                    'L_PAYMENTREQUEST_0_QTY'.$i => $item["qty"]
                ];
            $i++;

        }

        $paypal = new Paypal();
        $response = $paypal -> request('SetExpressCheckout',$requestParams + $pageParams + $orderParams + $items );

        if(is_array($response) && $response['ACK'] == 'Success') { //Request successful
            Log::info($response);
            $token = $response['TOKEN'];
            return Redirect::to(Config::get('punto-cms.paypal-redirect').'?cmd=_express-checkout&token=' . urlencode($token) );
        } else {

            Log::error($response);
            return $this->cancel();
        }

    }

    function paypalReturn(){

        $token = Input::get('token');
        $payer=Input::get('PayerID');

        if( isset($token) && !empty($token) ) { // Token parameter exists
            // Get checkout details, including buyer information.
            // We can save it for future reference or cross-check with the data we have
            $paypal = new Paypal();
            $checkoutDetails = $paypal->request('GetExpressCheckoutDetails', array('TOKEN' => $token));
            Log::info($checkoutDetails);

            Session::flash('warning','You must review your order and press the button below for your payment/order to be finalised!');
            return Redirect::to(Config::get('punto-cms.review-url')."?token=$token&PayerID=$payer");
        } else {

            Session::flash('error','Sorry, payment was not processed, please try again or contact info@bigbrew.com');
            return Redirect::to(Config::get('punto-cms.cart-url'));
        }
    }

    function paypalComplete(){
        $token = Input::get('token');
        $paypal = new Paypal();
        $checkoutDetails = $paypal->request('GetExpressCheckoutDetails', array('TOKEN' => $token));
        Log::info($checkoutDetails);
        $shipping=Session::get('shipping-review');
        // Complete the checkout transaction
        $requestParams = array(
            'TOKEN' => $token,
            'PAYMENTACTION' => 'Sale',
            'PAYERID' => Input::get('PayerID'),
            'PAYMENTREQUEST_0_AMT' => (Cart::instance('main')->total()+$shipping), // Same amount as in the original request
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'AUD' // Same currency as the original request
        );

        $response = $paypal->request('DoExpressCheckoutPayment', $requestParams);
        Log::info($response);
        if (is_array($response) && $response['ACK'] == 'Success') { // Payment successful
            // We'll fetch the transaction ID for internal bookkeeping
            $transactionId = $response['PAYMENTINFO_0_TRANSACTIONID'];
            Log::info('payment success: '.$transactionId);
            $checkoutDetails = $paypal->request('GetExpressCheckoutDetails', array('TOKEN' => $token));
            Log::info($checkoutDetails);
            return $this->complete($checkoutDetails);
        } else {
            return $this->cancel();
        }
    }

    function cancel(){
        Session::flash('error','Sorry, payment was not processed, please try again or contact info@bigbrew.com');
        return Redirect::to(Config::get('punto-cms.cart-url'));
    }

    function complete($checkoutDetails){
        Session::flash('success','Your order has been successfully completed and paid. You will receive an invoice at the email you provided and your order will be shipped as soon as possible.');

        Log::info('order completed: '.json_encode($checkoutDetails, JSON_PRETTY_PRINT).json_encode(Cart::instance('review')->content(), JSON_PRETTY_PRINT).json_encode(Session::get('shipping-review'), JSON_PRETTY_PRINT));

        Cart::instance('complete')->destroy();
        foreach(Cart::instance('review')->content()->toArray() as $key => $val){
            Cart::instance('complete')->add($val);
        }
        Cart::instance('review')->destroy();
        Cart::instance('main')->destroy();
        
        Session::put('shipping-complete',Session::get('shipping-review'));
        Session::remove('shipping-review');
        Session::remove('shipping');

        $order = new StoreOrder();
        $order->fields = json_encode($checkoutDetails, JSON_PRETTY_PRINT);
        $order->email = @$checkoutDetails["EMAIL"] ?: '';
        $order->payer_id = @$checkoutDetails["PAYERID"] ?: '';
        $order->payer_status = @$checkoutDetails["PAYERSTATUS"] ?: '';
        $order->first_name = @$checkoutDetails["FIRSTNAME"] ?: '';
        $order->last_name = @$checkoutDetails["LASTNAME"] ?: '';
        $order->country_code = @$checkoutDetails["COUNTRYCODE"] ?: '';
        $order->ship_to_name = @$checkoutDetails["SHIPTONAME"] ?: '';
        $order->ship_to_street = @$checkoutDetails["SHIPTOSTREET"] ?: '';
        $order->ship_to_city = @$checkoutDetails["SHIPTOCITY"] ?: '';
        $order->ship_to_state = @$checkoutDetails["SHIPTOSTATE"] ?: '';
        $order->ship_to_zip = @$checkoutDetails["SHIPTOZIP"] ?: '';
        $order->ship_to_country = @$checkoutDetails["SHIPTOCOUNTRYNAME"] ?: '';
        $order->currecncy_code = @$checkoutDetails["CURRENCYCODE"] ?: '';
        $order->note = @$checkoutDetails["PAYMENTREQUEST_0_NOTETEXT"] ?: '';
        $order->shipping = Session::get('shipping-complete') ?: null;
        $order->total = Cart::instance('complete')->total() + Session::get('shipping-complete');
        $order->completed = 1;
        $order->save();

        foreach(Cart::instance('complete')->content() as $row){

            $product = new StoreOrderProduct();
            $product->store_order_id = $order->id;
            $product->product = StoreProduct::find($row["options"]["product"])->name;
            $product->select = StoreSelectItem::find($row["options"]["select"])!==null?StoreSelectItem::find($row["options"]["select"])->name:'';
            $product->type_price = (StoreTypePrice::find($row["options"]["price"])!=null?StoreTypePrice::find($row["options"]["price"])->name:'');
            $product->quantity = $row["qty"];
            $product->price = $row["price"];
            $product->save();

        }

        if(Input::has('return'))
            return Redirect::to(Input::get('return'));
        else
            return Redirect::to(Config::get('punto-cms.complete-url'));
    }



    function routes(){
        Route::post('/cart','StoreCart@add');
        Route::post('/cart/update','StoreCart@update');
        Route::get('/cart/remove','StoreCart@remove');

        Route::get('/cart/paypal','StoreCart@paypal');
        Route::get('/cart/paypal/return','StoreCart@paypalReturn');
        Route::post('/cart/paypal/complete','StoreCart@paypalComplete');
        Route::get('/cart/paypal/cancel','StoreCart@cancel');
        //http://localhost:8000/paypal/return?token=EC-8YB61756R7325594X&PayerID=Z2PMSNVCD68JE
    }

}