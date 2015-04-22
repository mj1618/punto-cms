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
use File;
class Content extends Model {

    protected $table = 'content';

    function page(){
        return $this->belongsTo('App\AUI\Model\Page');
    }
    function item(){
        return $this->belongsTo('App\AUI\Model\Item');
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
