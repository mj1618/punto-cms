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


    function hasContent($name){
        return $this->findContent($name,null)!==null && strlen($this->findContent($name,null)->value)>0;
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
            ->get()
            ->first();

        $item = $this->section()->first()
            ->items()
            ->where('name','=',$name)->get()->first();

        if($item->itemType()->first()->short_name==='file-existing'){

            $i = File::find($c->value);

            if(!isset($i) || $i->value===''){
                return $def;
            }

            return str_replace('\\','/',$i->value);
        } else {
            return str_replace('\\','/',$c->value);
        }

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
            ->get()
            ->first();

        if(!isset($c) || $c->value===''){
            $c = new Content();
            $c->value = $def;
        }

        return $c;
    }
}
