<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Log;
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

    function attachments(){
        return $this->hasMany('App\AUI\Model\Attachment');
    }


    function hasContent($name,$def=''){

        $n = $this
            ->contents()
            ->whereIn(
                'item_id',
                $this->section()->first()
                    ->items()
                    ->where('name','=',$name)
                    ->lists('id'))
            ->count();

        return $n > 0;
    }

    function findImage($name,$def=''){

        $c = $this
            ->contents()
            ->whereIn(
                'item_id',
                $this->section()->first()
                    ->items()
                    ->where('name','=',$name)
                    ->lists('id'))
            ->first();

        $i = File::find($c->value);

        if(!isset($i) || $i->value===''){
            return $def;
        }

        return str_replace('\\','/',$i->value);
    }

    function findContent($name,$def=''){

        $c = $this
            ->contents()
            ->whereIn(
                'item_id',
                $this->section()->first()
                    ->items()
                    ->where('name','=',$name)
                    ->lists('id'))
            ->first();

        if(!isset($c) || $c->value===''){
            $c = new Content();
            $c->value = $def;
        }

        return $c;
    }
}
