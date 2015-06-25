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

class PageContentForm extends FormController {

    function __construct(){
        $this->controllerClass = 'PageContentForm';
        $this->header = 'Edit Page Section Content';
        $this->baseRoute = '/admin/edit-pages/{id1}/posts/{id2}/content';
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

        $sectionId = Post::find(Request::route('id2'))->section()->first()->id;
        
        foreach(Item::where('section_id','=',$sectionId)->get() as $item){
            $content = $item->content()->where('post_id','=',Request::route('id2'))->get()->first();
            $is = $item->render($is,$content);
        }

        return parent::definition([
            'Form ID'=>$this->getHeader()."-form",
            'Submit Button Text'=>'Save',
            'Inputs' => $is
        ]);
    }

    function post(){

//        $ref = Request::header("referrer");

        foreach($this->definition()["Inputs"] as $input){
            $item = Item::find($input->id);
            $content = $item->content()->where('post_id','=',Request::route('id2'))->get()->first();


            if(isset($content)===false){
                $content = new Content();
                $content->item_id = $item->id;
                $content->post_id = Request::route('id2');
                $input->insert($content, Input::get("$item->id"));
            } else {
                $input->update($content, Input::get("$item->id"));
            }

//            $content->value = Input::get("$item->id");

            $content->save();
        }


        return Redirect::to("/admin/edit-pages/".Request::route("id1")."/view");
    }

}