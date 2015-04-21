<?php namespace App\AUI\Controllers;


use App\AUI\Model\Attachment;
use App\AUI\Model\ItemType;
use App\AUI\Model\Post;
use App\AUI\Model\Section;
use App\AUI\Model\Page;
use App\AUI\Model\PageSection;
use App\AUI\Model\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\FileInput;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\ChildTable;
use Request;
use Route;
class PageSummaryAttachments extends Table2 {

    function __construct(){
        parent::controllerClass('PageSummaryAttachments');
        parent::headerPlural('Page Documents');
        parent::headerSingular('Page Document');
        parent::baseRoute('/admin/manage-pages/{id1}/posts/{id2}/attachments');
        parent::ajaxBaseRoute('/ajax/admin/manage-pages/{id1}/posts/{id2}/attachments');
        parent::table(new Attachment());
        $this->level=3;
        parent::attributes([
                [
                    'title'=>'ID',
                    'id'=>'id'
                ],
                [
                    'title'=>'Post',
                    'id'=>'post.name'
                ],
                [
                    'title'=>'Name',
                    'id'=>'name'
                ],
                [
                    'title'=>'Type',
                    'id'=>'item_type.name'
                ]
            ]);

        parent::inputs(function($row) {
                $pageId = Request::route("id1");
                $page = Page::find($pageId);
                $postId = Request::route("id2");
                $post = Post::find($postId);

                return [
                    'name' => (new TextBox)
                        ->id('name')
                        ->label('Name')
                        ->defaultValue($row?$row->name:''),
                    'post_id' => (new MetaItem())->id('post_id')->defaultValue($post->id),
                    'file' =>
                        (new FileInput())
                            ->id('value')
                            ->valueField('value')
                            ->label('File')
                            ->filename($row?$row->value:'')
                            ->defaultValue($row?$row->value:''),
                    'item_type_id' => (new DropDown())
                        ->id('item_type_id')
                        ->nullable(false)
                        ->label('Type')
                        ->idField('id')
                        ->nameField('name')
                        ->rows(ItemType::whereIn('short_name',["image","file"])->get())
                        ->defaultValue($row?$row->item_type_id:'')
                ];
        });
        $this->parentHeader='Edit Page';
        parent::tableName('attachment');
    }

    function getCrumbs($header, $ps=null){
        return ViewUtils::blank();
    }

    function getCreateReturnUrl(){
        return "/admin/manage-pages/".Request::route('id1')."/content";
    }
    function getEditReturnUrl(){
        return "/admin/manage-pages/".Request::route('id1')."/content";
    }

    function buttons(){
        return [
            "edit" => [
                'id'=>$this->getHeaderSingular()."-edit",
                'text'=>'Edit',
                'requiresSelect'=>'true',
                'url'=>$this->getEditPartialRoute()
            ],
            "download" => [
                'id'=>$this->getHeaderSingular()."-download",
                'text'=>'Download',
                'requiresSelect'=>'true',
                'url'=>$this->getBaseUrl()."/{id}/download",
                'newTab'=>'true'
            ],
            "create" => [
                'id'=>$this->getHeaderSingular()."-create",
                'text'=>'Create',
                'requiresSelect'=>'false',
                'url'=>$this->getCreateUrl(),
                'float'=>'left'
            ]
        ];
    }

    function download(){
        $att = Attachment::find(Request::route("id3"));
        return response()->download("$att->value");
    }

    function routes(){
        Route::get($this->getBaseRoute()."/{id3}/download",["as"=>"Download Attachment","uses"=>"PageSummaryAttachments@download"]);
        parent::routes();
    }

//    function buttons(){
//        return [
//            "view" => [
//                'id'=>$this->getHeaderSingular()."-view",
//                'text'=>'View',
//                'requiresSelect'=>'true',
//                'url'=>$this->getViewPartialRoute()
//            ],
//            "create" => [
//                'id'=>$this->getHeaderSingular()."-create",
//                'text'=>'Create',
//                'requiresSelect'=>'false',
//                'url'=>$this->getCreateUrl(),
//                'float'=>'left'
//            ]
//        ];
//    }

    function breadcrumbs(){
    }



    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
//            (new TemplateSectionItems())->showAllView()
            (new PageContentForm())->showViewBox()

        ];
    }

    function dataAll(){
        return $this->table
            ->whereIn(
                "post_id",
                Post::where('page_id','=',Request::route("id".($this->level-1)))->lists('id'))
            ->with('post')
            ->with('itemType')
            ->get();
    }
}