<?php namespace App\AUI\Model;

use DateTime;
use DateTimeZone;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Content extends Model {

    protected $table = 'content';

    function page(){
        return $this->belongsTo('App\AUI\Model\Page');
    }
    function item(){
        return $this->belongsTo('App\AUI\Model\Item');
    }


    function url(){

//        if (DIRECTORY_SEPARATOR == '/') {
//            return str_replace('\\','/',$this->value);
//        } else if (DIRECTORY_SEPARATOR == '\\') {
//            return str_replace('/','\\',$this->value);
//        } else {
//            return str_replace('\\','/',$this->value);
//        }

        return str_replace('\\','/',$this->value);
    }

    function base64Image(){

        if (DIRECTORY_SEPARATOR == '/') {
            return base64_encode(File::get(str_replace('\\','/',$this->value)));
        } else if (DIRECTORY_SEPARATOR == '\\') {
            return base64_encode(File::get(str_replace('/','\\',$this->value)));
        } else {
            return base64_encode(File::get(str_replace('\\','/',$this->value)));
        }

    }


    function getImage(){

//        if (DIRECTORY_SEPARATOR == '/') {
//            return str_replace('\\','/',$this->value);
//        } else if (DIRECTORY_SEPARATOR == '\\') {
//            return str_replace('/','\\',$this->value);
//        } else {
//            return str_replace('\\','/',$this->value);
//        }

        $f = File::find($this->value);
        return $f===null?'':str_replace('\\','/',$f->value);
    }

    function getDropdownItem(){

//        if (DIRECTORY_SEPARATOR == '/') {
//            return str_replace('\\','/',$this->value);
//        } else if (DIRECTORY_SEPARATOR == '\\') {
//            return str_replace('/','\\',$this->value);
//        } else {
//            return str_replace('\\','/',$this->value);
//        }

        $i = ItemValue::find($this->value);
        return $i;
    }


    function events(){

        $events = '';

        if(Cache::has($this->value)){
            $events = Cache::get($this->value);
        } else {
            $cal = new \om\IcalParser();
            $cal->parseFile($this->value);
            $events = array_filter($cal->getSortedEvents(),function($e){
                $tz = new DateTimeZone(Config::get('app.timezone'));
                $e['DTSTART']->setTimezone($tz);
                $e['DTEND']->setTimezone($tz);

                return $e['DTSTART'] >= (new DateTime("now"));
            });
            Cache::put($this->value, $events, 10);
        }

        return $events;
    }
}
