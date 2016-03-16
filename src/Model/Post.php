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
                return $c->value;
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

    function getNameFormattedAttribute(){
        $postName = $this->name;

        if($this->hasContent("Title")){
            $postName = $this->findContent("Title")->value;
        } else if($this->hasContent("Name")){
            $postName = $this->findContent("Name")->value;
        }else if($this->hasContent("Description")){
            $desc = $this->findContent("Description")->value;
            if (strlen($desc) > 20)
                $desc = substr($desc, 0, 17) . '...';
            $postName = $desc;
        } else {
            foreach($this->contents()->get() as $c){
                $type = $c->itemType();
                if($type && $type->short_name=='textbox' && $c->value!=null){
                    $postName=$c->value;
                }
            }
        }
        return $postName;
    }

    protected $appends = array('name_formatted');
}
