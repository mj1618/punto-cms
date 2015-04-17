<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Page;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\Template;
use App\AUI\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
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

class Pages extends Table2 {


    function __construct(){
        parent::controllerClass('Pages');
        parent::headerPlural('Pages');
        parent::headerSingular('Page');
        parent::baseRoute('/admin/pages');
        parent::ajaxBaseRoute('/ajax/admin/pages');
        parent::table(new Page());
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
                    ->defaultValue($row ? $row->url : ''),
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

    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
//            (new TemplateCategories())->showAllView()

        ];
    }

    function dataAll(){
        return $this->table->with('template')->get();
    }

}