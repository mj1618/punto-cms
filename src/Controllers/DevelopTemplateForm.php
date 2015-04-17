<?php namespace App\AUI\Controllers;


use App\AUI\Model\Content;
use App\AUI\Model\Item;
use App\AUI\Model\ItemValue;
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

class DevelopTemplateForm extends FormController {

    function __construct(){
        $this->controllerClass = 'DevelopTemplateForm';
        $this->header = 'Edit Template Code';
        $this->baseRoute = '/admin/template/{id1}/develop';
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

        $row = Template::find(Request::route('id1'));

        $is[] = (new CodeInput())
                    ->id('code')
                    ->label('Code')
                    ->isRow(true)
                    ->lgCols(12)
                    ->defaultValue($row ? file_get_contents(base_path()."/resources/views/aui/templates/".$row->filename) : '');

        return parent::definition([
            'Form ID'=>$this->getHeader()."-form",
            'Submit Button Text'=>'Save',
            'Inputs' => $is
        ]);
    }

    function post(){

        $template = Template::find(Request::route('id1'));
        $file = base_path()."/resources/views/aui/templates/".$template->filename;
        file_put_contents($file, Input::get('code'));


        return Redirect::to("/admin/templates");
    }

}