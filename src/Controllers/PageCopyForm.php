<?php namespace App\AUI\Controllers;


use App\AUI\Model\Attachment;
use App\AUI\Model\Content;
use App\AUI\Model\Item;
use App\AUI\Model\ItemValue;
use App\AUI\Model\Page;
use App\AUI\Model\PageSection;
use App\AUI\Model\Post;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use MJ1618\AdminUI\Controller\ChildFormController;
use MJ1618\AdminUI\Controller\FormController;
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
use MJ1618\AdminUI\Form\TimeInput;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\ChildTable;
use Request;

class PageCopyForm extends FormController {

    function __construct(){
        $this->controllerClass = 'PageCopyForm';
        $this->header = 'Copy Page';
        $this->baseRoute = '/admin/edit-pages/{id1}/copy';
//        $this->plainPage=true;
        $this->suffix='';
        $this->infoMessages = [''];
        if(Session::has('error'))$this->errorMessages = [Session::get('error')];
    }

    function getCrumbs(){
        return ViewUtils::blank();
    }


    function definition($def=[]){

        $is = [];

        $is[] = (new TextBox())->id('name')->label('New Page Name');
        $is[] = (new PlainTextAreaBox())->id('description')->label('New Page description');
        $is[] = (new TextBox())->id('url')->label('New Page URL')->defaultValue("/".substr( md5(rand()), 0, 6));


        return parent::definition([
            'Form ID'=>$this->getHeader()."-form",
            'Submit Button Text'=>'Save',
            'Inputs' => $is
        ]);
    }

    function post(){

        $page = Page::find(Request::route("id1"))->copy(Input::get('name'), Input::get('description'), Input::get('url'));



        return Redirect::to("/admin/edit-pages/".$page->id."/view");
    }

}