<?php namespace App\AUI\Controllers;


use App\AUI\Model\Attachment;
use App\AUI\Model\Content;
use App\AUI\Model\Item;
use App\AUI\Model\ItemType;
use App\AUI\Model\ItemValue;
use App\AUI\Model\Page;
use App\AUI\Model\PageSection;
use App\AUI\Model\Post;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use MJ1618\AdminUI\Controller\ChildFormController;
use MJ1618\AdminUI\Controller\FormController;
use MJ1618\AdminUI\Form\ButtonItem;
use MJ1618\AdminUI\Form\Checkbox;
use MJ1618\AdminUI\Form\DateRange;
use MJ1618\AdminUI\Form\DateTimeRange;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\FileInput;
use MJ1618\AdminUI\Form\HeaderItem;
use MJ1618\AdminUI\Form\ImageInput;
use MJ1618\AdminUI\Form\LinkItem;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\NumberInput;
use MJ1618\AdminUI\Form\PlainDropDown;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Form\TextOutputItem;
use MJ1618\AdminUI\Form\TimeInput;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\ChildTable;
use MJ1618\AdminUI\Utils\ViewWrapper;

class PageSummaryNewAttachmentForm extends FormController {

    function __construct(){
        $this->controllerClass = 'PageSummaryNewAttachmentForm';
        $this->header = 'Add Attachment';
        $this->baseRoute = '/admin/manage-pages/{id1}/posts/{id2}/new-attachment';
//        $this->plainPage=true;
        $this->suffix='';
        $this->infoMessages = [''];
        if(Session::has('error'))$this->errorMessages = [Session::get('error')];
    }

//    function routes(){
//        parent::routes();
//        Route::get('/admin/manage-pages/{id1}/posts/{id2}/attachment/{id3}/edit', ['as' => $this->getHeader(), 'uses'=>"$this->controllerClass@show"]);
//    }



    function getCrumbs(){
        return ViewUtils::blank();
    }

    function inputs($post,$att=null){

        $is = [];

        return [
            'name' => (new TextBox)
                ->id('name')
                ->label('Name')
                ->defaultValue($att?$att->name:''),
            'post_id' => (new MetaItem())
                ->id('post_id')
                ->defaultValue($post->id),
            'file' =>
                (new FileInput())
                    ->id('value')
                    ->valueField('value')
                    ->label('File')
                    ->filename($att?$att->value:'')
                    ->defaultValue($att?$att->value:''),
            'item_type_id' => (new DropDown())
                ->id('item_type_id')
                ->nullable(false)
                ->label('Type')
                ->idField('id')
                ->nameField('name')
                ->rows(ItemType::whereIn('short_name',["image","file"])->get())
                ->defaultValue($att?$att->item_type_id:'')
        ];
    }


    function definition($def=[]){


        return parent::definition([
            'Form ID'=>$this->getHeader()."-form",
            'Submit Button Text'=>'Save',
            'Inputs' => $this->inputs($this->currentPost(),$this->currentAttachment())
        ]);
    }

    function currentPost(){
        $postId = Request::route('id2');
        if(isset($postId))
            return Post::find($postId);
        else return null;
    }
    function currentAttachment(){
        $attId = Request::route('id3');
        if(isset($attId))
            return Attachment::find($attId);
        else return null;
    }

    function post(){

        $att = $this->currentAttachment();
        if(!isset($att)){
            $att = new Attachment();
        }
//        $post = $this->currentPost();
        $pageId = Request::route('id1');

        $this->processInputs($att);
        $att->save();

        return Redirect::to("/admin/manage-pages/$pageId/content");
    }

}