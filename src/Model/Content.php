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
use ICal;

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

        if(is_numeric($this->value))
            $f = File::find($this->value)->value;
        else
            $f = $this->value;
        return $f===null?'':str_replace('\\','/',$f);
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


    function events($limit = null){

        $events = '';

        if(Cache::has($this->value)){
            $events = Cache::get($this->value);
        } else {
            $ical   = new ICal($this->value);
            $events = $ical->sortEventsWithOrder($ical->eventsFromRange('now'));
            Cache::put($this->value, $events, 10);
        }

        if(isset($limit)){
            $events = array_slice($events,0,$limit);
        }

        return $events;
    }
}
