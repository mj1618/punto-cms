<?php namespace App\AUI\Controllers;


use App\AUI\Model\Content;
use App\AUI\Model\Item;
use App\AUI\Model\ItemValue;
use App\AUI\Model\PageSection;
use App\AUI\Model\Post;
use App\AUI\Controllers\PostAttachments;
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

class PageSummaryPostsForm extends FormController {

    function __construct(){
        $this->controllerClass = 'PageSummaryPostsForm';
        $this->header = 'Edit Post';
        $this->baseRoute = '/admin/manage-pages/{id1}/posts/{id2}/edit';
//        $this->plainPage=true;
        $this->suffix='';
        $this->infoMessages = [''];
        if(Session::has('error'))$this->errorMessages = [Session::get('error')];
    }

    function postViews($postId){
        $views = [];
        $pageId= Request::route('id1');
        $post = Post::find($postId);

        foreach($this->inputs($post) as $i){
            $views[] = new ViewWrapper(function() use($i) { return $i->renderView12(); });
        }

        $attViews=[];
//        $attViews[] = (new HeaderItem())->label('Attachments');
        $attViews[] = ViewUtils::col6([(new PostAttachments($postId))->showTable()]);
//        $attViews[] = ViewUtils::col12([(new ButtonItem())->cssClass("btn-default btn-sm")->label('New Attachment')->defaultValue("/admin/manage-pages/".$pageId."/posts/".$post->id."/attachments/create")]);

        if($post->section()->get()->first()->has_attachments === 1)
            $views[] = ViewUtils::col12([ViewUtils::accordion(
                "".rand(0,100),
                'Attachments',
                [
                    [
                        "header"=> 'Attachments',
                        "body"=> ViewUtils::row($attViews),
                        "id"=> "".rand(0,100),
                        "buttons"=>[(new ButtonItem())->cssClass("btn-default btn-sm")->label('New Attachment')->defaultValue("/admin/manage-pages/".$pageId."/posts/".$post->id."/attachments/create")],
                        "icon"=>"fa-file"
                    ]
                ] ,12,0)]);
        return [ViewUtils::row($views)];
    }

    function getCrumbs(){
        return ViewUtils::blank();
    }

    function inputs($post){

        $is = [];

        $sectionId = $post->section()->get()->first()->id;

        foreach(Item::where('section_id','=',$sectionId)->get() as $item){
            $content = $item->content()->where('post_id','=',$post->id)->get()->first();
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
            'Inputs' => $this->inputs(Post::find(Request::route('id2')))
        ]);
    }

    function post(){
        $post = Post::find(Request::route('id2'));
        $pageId = Request::route('id1');
        $sectionId = $post->section()->first()->id;

        $inputs = $this->inputs(Post::find(Request::route('id2')));

        foreach($inputs as $input){
            $item = Item::find($input->id);
            $content = $item->content()->where('post_id','=',$post->id)->get()->first();


            if(isset($content)===false){
                $content = new Content();
                $content->item_id = $item->id;
                $content->post_id = $post->id;
                $input->insert($content, Input::get("$item->id"));
            } else {
                $input->update($content, Input::get("$item->id"));
            }

            $content->save();
        }



        return Redirect::to("/admin/manage-pages/$pageId/content");
    }

}