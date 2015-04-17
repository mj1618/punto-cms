<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Item extends Model {

    protected $table = 'item';

    function section(){
        return $this->belongsTo('App\AUI\Model\Section');
    }
    function itemType(){
        return $this->belongsTo('App\AUI\Model\ItemType');
    }

    function content(){
        return $this->hasMany('App\AUI\Model\Content');
    }

}
