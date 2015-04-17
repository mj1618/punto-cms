<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Attachment extends Model {

    protected $table = 'attachment';

    function post(){
        return $this->belongsTo('App\AUI\Model\Post');
    }

    function itemType(){
        return $this->belongsTo('App\AUI\Model\ItemType');
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
}
