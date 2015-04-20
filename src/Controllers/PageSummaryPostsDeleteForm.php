<?php namespace App\AUI\Controllers;


use App\AUI\Model\Content;
use App\AUI\Model\Item;
use App\AUI\Model\ItemValue;
use App\AUI\Model\PageSection;
use App\AUI\Model\Post;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
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

class PageSummaryPostsDeleteForm extends FormController {

    function __construct(){
        $this->controllerClass = 'PageSummaryPostsDeleteForm';
        $this->header = 'Delete Post';
        $this->baseRoute = '/admin/manage-pages/{id1}/posts/{id2}/delete';
//        $this->plainPage=true;
        $this->suffix='';
        $this->infoMessages = [''];
        $this->template = "admin-ui::modal";
        if(Session::has('error'))$this->errorMessages = [Session::get('error')];
    }

    function getCrumbs(){
        return ViewUtils::blank();
    }
    function showView($views){

        $post = Post::find(Request::route('id2'));
        $pageId = Request::route('id1');
        $buttons = [];
        $buttons[] = [
            "submit"=>true,
            "label"=>"Delete Post",
            "type"=>" bg-purple"
        ];
        $buttons[] = [
            "submit"=>false,
            "href"=>"/admin/manage-pages/$pageId/content",
            "label"=>"Cancel",
            "type"=>"default"
        ];

        $allViews = [];
        $allViews[] = ViewUtils::modal("Deleting Post: $post->name","Are you sure you want to delete post \"$post->name\" ?", $buttons,$this->getUrl());


        if($this->plainPage===true){
            return ViewUtils::plainPage($allViews);
        } else {
            return ViewUtils::page($allViews);
        }
    }


    function post(){
        $post = Post::find(Request::route('id2'));
        $pageId = Request::route('id1');

        $post->delete();

        return Redirect::to("/admin/manage-pages/$pageId/content");
    }

}