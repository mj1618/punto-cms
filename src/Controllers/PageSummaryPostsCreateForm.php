<?php namespace App\AUI\Controllers;


use App\AUI\Model\Content;
use App\AUI\Model\Item;
use App\AUI\Model\ItemValue;
use App\AUI\Model\PageSection;
use App\AUI\Model\Post;
use App\AUI\Controllers\PostAttachments;
use App\AUI\Model\Section;
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

class PageSummaryPostsCreateForm extends FormController {

    function __construct(){
        $this->controllerClass = 'PageSummaryPostsCreateForm';
        $this->header = 'Create Post';
        $this->baseRoute = '/admin/manage-pages/{id1}/section/{id2}/add-post';
//        $this->plainPage=true;
        $this->suffix='';
        $this->infoMessages = [''];
        if(Session::has('error'))$this->errorMessages = [Session::get('error')];
    }

    function getCrumbs(){
        return ViewUtils::blank();
    }

    function inputs(){

        $is = [];

        $sectionId = Request::route('id2');

        foreach(Item::where('section_id','=',$sectionId)->get() as $item){
//            $content = $item->content()->where('post_id','=',$post->id)->get()->first();
            $content = null;
            switch($item->itemType()->first()->short_name){
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
                            ->selected($content?$content->value:0);
                    break;
                case "image":
                    $is[] =
                        (new ImageInput())
                            ->id($item->id)
                            ->valueField('value')
                            ->label($item->name)
                            ->filename($content?$content->filename:'')
                            ->defaultValue($content?$content->value:'');
                    break;
                case "file":
                    $is[] =
                        (new FileInput())
                            ->id($item->id)
                            ->valueField('value')
                            ->label($item->name)
                            ->filename($content?$content->value:'')
                            ->defaultValue($content?$content->value:'');
                    break;
            }

        }

        return $is;
    }

    function definition($def=[]){


        return parent::definition([
            'Form ID'=>$this->getHeader()."-form",
            'Submit Button Text'=>'Save',
            'Inputs' => $this->inputs()
        ]);
    }

    function post(){
        $pageId = Request::route('id1');
        $sectionId = Request::route('id2');
        $section = Section::find($sectionId);

        $inputs = $this->inputs();

        $post = new Post();
        $post->page_id=$pageId;
        $post->section_id=$sectionId;
        $post->name=$section->name;
        $post->description=$section->description;
        $post->save();


        foreach($inputs as $input){
            $item = Item::find($input->id);
            $content = null;


            if(isset($content)===false){
                $content = new Content();
                $content->item_id = $item->id;
                $content->post_id = $post->id;
                $input->insert($content, Input::get("$item->id"));
            }
//            else {
//                $input->update($content, Input::get("$item->id"));
//            }

            $content->save();
        }



        return Redirect::to("/admin/manage-pages/$pageId/content");
    }

}