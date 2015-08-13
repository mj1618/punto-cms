<?php namespace App\AUI\Controllers;


use App\AUI\Model\Content;
use App\AUI\Model\Item;
use App\AUI\Model\ItemValue;
use App\AUI\Model\Language;
use App\AUI\Model\Page;
use App\AUI\Model\PageSection;
use App\AUI\Model\Template;
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
use MJ1618\AdminUI\Form\CodeInput;
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

class PageNew extends FormController {

    function __construct(){
        $this->controllerClass = 'PageNew';
        $this->header = 'New Page';
        $this->baseRoute = '/admin/new-page';
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

        $row = null;

        $is = [
            'name' => (new TextBox)
                ->id('name')
                ->label('Page Name')
                ->defaultValue($row ? $row->name : ''),
            'description' => (new PlainTextAreaBox())
                ->id('description')
                ->label('Page Description')
                ->defaultValue($row ? $row->description : ''),
            'url' => (new TextBox())
                ->id('url')
                ->label('URL')
                ->defaultValue($row ? $row->url : "/".substr( md5(rand()), 0, 6)),
            'template_id' => (new DropDown())
                ->id('template_id')
                ->nullable(false)
                ->label('Template')
                ->idField('id')
                ->nameField('name')
                ->rows(Template::get())
                ->defaultValue($row ? $row->template_id : ''),
            'language_id' => (new DropDown())
                ->id('language_id')
                ->nullable(false)
                ->label('Language')
                ->idField('id')
                ->nameField('name')
                ->rows(Language::get())
                ->defaultValue($row ? $row->language_id : '')
        ];

        return parent::definition([
            'Form ID'=>$this->getHeader()."-form",
            'Submit Button Text'=>'Save',
            'Inputs' => $is
        ]);
    }

    function post(){

        $page = new Page();
        $page->name = Input::get('name');
        $page->description = Input::get('description');
        $page->url = Input::get('url');
        $page->template_id=Input::get('template_id');
        $page->language_id=Input::get('language_id');
        $page->save();

        return Redirect::to("/admin/manage-pages/".$page->id."/content");
    }

}