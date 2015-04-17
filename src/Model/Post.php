<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Post extends Model {

    protected $table = 'post';

    function page(){
        return $this->belongsTo('App\AUI\Model\Page');
    }
    function section(){
        return $this->belongsTo('App\AUI\Model\Section');
    }
    function contents(){
        return $this->hasMany('App\AUI\Model\Content');
    }

    function findContent($name){

        $c = $this
            ->contents()
            ->whereIn(
                'item_id',
                $this->section()->first()
                    ->items()
                    ->where('name','=',$name)
                    ->lists('id'))
            ->first();

        if(!isset($c)){
            $c = new Content();
            $c->value = '';
        }

        return $c;
    }
}
