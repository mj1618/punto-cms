<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use File;
class Feed extends Model {

    protected $table = 'feed';

    function section(){
        return $this->belongsTo('App\AUI\Model\Section');
    }
    function page(){
        return $this->belongsTo('App\AUI\Model\Page');
    }


}
