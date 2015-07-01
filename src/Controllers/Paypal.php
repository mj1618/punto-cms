<?php namespace App\AUI\Controllers;
use Config;
use Log;
class Paypal {
    public function request($method,$params = array()) {
        $this -> _errors = array();
        if( empty($method) ) { //Check if API method is not empty
            $this -> _errors = array('API method is missing');
//            Log::error('API method is missing');
            return false;
        }

        //Our request parameters
        $requestParams = [
            'METHOD' => $method,
            'VERSION' => Config::get('punto-cms.paypal-version'),
            'USER' => Config::get('punto-cms.paypal-user'),
            'PWD' => Config::get('punto-cms.paypal-password'),
            'SIGNATURE' => Config::get('punto-cms.paypal-signature')
        ];

        //Building our NVP string
        $request = http_build_query($requestParams + $params);

        //cURL settings
        $curlOptions = array (
            CURLOPT_URL => Config::get('punto-cms.paypal-endpoint'),
            CURLOPT_VERBOSE => 1,
//            CURLOPT_SSL_VERIFYPEER => true,
//            CURLOPT_SSL_VERIFYHOST => 2,
//            CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', //CA cert file
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $request
        );

        $ch = curl_init();
        curl_setopt_array($ch,$curlOptions);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error(curl_error($ch));
            curl_close($ch);
            return false;
        } else  {
            Log::info($response);
            curl_close($ch);
            $responseArray = array();
            parse_str($response,$responseArray);
            return $responseArray;
        }
    }

}