<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use MJ1618\AdminUI\Form\Checkbox;
use MJ1618\AdminUI\Form\DateRange;
use MJ1618\AdminUI\Form\DateTimeRange;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\FileInput;
use MJ1618\AdminUI\Form\HeaderItem;
use MJ1618\AdminUI\Form\ImageInput;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\NumberInput;
use MJ1618\AdminUI\Form\PlainDropDown;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Form\ImageSelect;
use MJ1618\AdminUI\Form\ElfinderImageInput;
use MJ1618\AdminUI\Form\ElfinderFileInput;
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


    function render($is,$content){
        $item=$this;
        switch($item->itemType()->get()->first()->short_name){
            case "textbox":
                $is[] =
                    (new TextBox())
                        ->id($item->id)
                        ->label($item->name)
                        ->valueField('value')
                        ->defaultValue($content?$content->value:'');
                break;
            case "textarea":
                $is[] =
                    (new TextAreaBox())
                        ->id($item->id)
                        ->label($item->name)
                        ->images(File::all())
                        ->valueField('value')
                        ->defaultValue($content?$content->value:'');
                break;
            case "plaintextarea":
                $is[] =
                    (new PlainTextAreaBox())
                        ->id($item->id)
                        ->label($item->name)
                        ->valueField('value')
                        ->defaultValue($content?$content->value:'');
                break;
            case "dropdown":
                $is[] =
                    (new DropDown())
                        ->id($item->id)
                        ->label($item->name)
                        ->idField('id')
                        ->nameField('value')
                        ->valueField('value')
                        ->rows(ItemValue::where('item_id','=',$item->id)->get())
                        ->printValue($content&&ItemValue::find($content->value)?ItemValue::find($content->value)->value:'')
                        ->defaultValue($content?$content->value:'');
                break;
            case "checkbox":
                $is[] =
                    (new Checkbox())
                        ->id($item->id)
                        ->valueField('value')
                        ->label($item->name)
                        ->defaultValue($content?$content->value:0);
                break;
            case "file":
                $is[] =
                    (new ElfinderFileInput())
                        ->id($item->id)
                        ->valueField('value')
                        ->label($this->name)
                        ->defaultValue($content?$content->value:null);
                break;

            case "image":
            case "file-existing":
                $is[] =
                    (new ElfinderImageInput())
                        ->id($item->id)
                        ->valueField('value')
                        ->label($this->name)
                        ->defaultValue($content?$content->value:null);

//                ->idField('id')
//                ->nameField('filename')
//                ->valueField('value')
//                ->rows(File::orderBy('created_at','DESC')->get())
                break;
        }
        return $is;
    }
}
