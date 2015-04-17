<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Page;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\Template;
use App\AUI\Model\User;
use App\AUI\Controllers\PageContentForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Log;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\HiddenInput;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\NumberInput;
use MJ1618\AdminUI\Form\PasswordInput;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\Table;
use Route;
use Request;
class EditPages extends Table2 {


    function __construct(){
        parent::controllerClass('EditPages');
        parent::headerPlural('Edit Pages');
        parent::headerSingular('Edit Page');
        parent::baseRoute('/admin/edit-pages');
        parent::ajaxBaseRoute('/ajax/admin/edit-pages');
        parent::table(new Page());
        $this->useEditButton=false;
        parent::attributes([
            [
                'title'=>'ID',
                'id'=>'id'
            ],
            [
                'title'=>'Name',
                'id'=>'name'
            ],
            [
                'title'=>'URL',
                'id'=>'url'
            ],
            [
                'title'=>'Template',
                'id'=>'template.name'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row ? $row->name : ''),
                'description' => (new PlainTextAreaBox())
                    ->id('description')
                    ->label('Description')
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
                    ->defaultValue($row ? $row->template_id : '')
            ];
        });
        parent::tableName('page');
    }

    function buttons(){
        return [
            "open" => [
                'id'=>$this->getHeaderSingular()."-open",
                'text'=>'Open Page',
                'requiresSelect'=>'true',
                'newTab'=>'true',
                'url'=>$this->getBaseUrl()."/{id}/open"
            ],
            "copy" => [
                'id'=>$this->getHeaderSingular()."-copy",
                'text'=>'Copy Page',
                'requiresSelect'=>'true',
                'url'=>$this->getBaseUrl()."/{id}/copy"
            ],
            "view" => [
                'id'=>$this->getHeaderSingular()."-view",
                'text'=>'Manage Content',
                'requiresSelect'=>'true',
                'url'=>$this->getViewPartialRoute()
            ],
            "create" => [
                'id'=>$this->getHeaderSingular()."-create",
                'text'=>'New Page',
                'requiresSelect'=>'false',
                'url'=>$this->getCreateUrl(),
                'float'=>'left'
            ]
        ];
    }

    function routes(){
        parent::routes();
        Route::get("$this->baseRoute/{id$this->level}/open","EditPages@openPage");
    }

    function openPage(){
        $url = Page::find(Request::route("id$this->level"))->url;
        return Redirect::to($url);
    }

    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
//            (new TemplateCategories())->showAllView()
            (new EditPagesPosts())->showAllView(),
            (new EditPagesAttachments())->showAllView(),
            (new PagePreview())->show()
        ];
    }

    function dataAll(){

        if(Auth::user()->userPages()->count()===0){
            return $this->table->with('template')->get();
        } else {
            return $this->table->whereIn('id', Auth::user()->userPages()->lists('id'))->with('template')->get();
        }

    }

}