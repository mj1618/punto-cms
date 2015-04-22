<?php namespace App\AUI\Controllers;


use App\AUI\Model\Page;
use App\SS\Model\Enrolment;
use App\SS\Model\Form;
use App\SS\Model\FormTimePeriod;
use App\SS\Model\Level;
use App\SS\Model\Location;
use App\SS\Model\RegistrationSession;
use App\SS\Model\Stream;
use App\SS\Model\SwimClass;
use App\SS\Model\Swimmer;
use App\SS\Model\WeekDay;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use MJ1618\AdminUI\Controller\ChildFormController;
use MJ1618\AdminUI\Controller\FormController;
use MJ1618\AdminUI\Controller\ListController;
use MJ1618\AdminUI\Form\DateRange;
use MJ1618\AdminUI\Form\DateTimeRange;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\HeaderItem;
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
use Request;
use DB;
class PagesList extends ListController {

    function __construct(){
        $this->controllerClass = 'PagesList';
        $this->header = 'Select a Page';
        $this->baseRoute = '/admin/manage-pages';
        $this->plainPage=false;
//        $this->infoMessages = ['Registration and Payment Complete!'];
    }

    function getCrumbs(){
        return ViewUtils::blank();
    }


    function definition($def=[]){
        $items = [];
        $pages=null;
        if(Auth::user()->userPages()->count()===0)
            $pages = Page::get();
        else
            $pages = Page::whereIn('id',Auth::user()->userPages()->lists('page_id'))->get();

        $pages->each(function($page) use(&$items){
                $labels = [];

                $items["$page->id"] = array_merge([
                    'title'=>$page->name,
                    'description'=>$page->description,
                    'href'=>"/admin/manage-pages/$page->id/content",
                    'label1'=>$page->template()->get()->first()->name,
                    'label2'=>$page->url

                ],$labels);
        });

        return [
            'id'=>'page-list',
            'items'=>$items
            ];
    }

}