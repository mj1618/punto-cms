<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Feed;
use App\AUI\Model\Page;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\Section;
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

class Feeds extends Table2 {


    function __construct(){
        parent::controllerClass('Feeds');
        parent::headerPlural('Feeds');
        parent::headerSingular('Feed');
        parent::baseRoute('/admin/feeds');
        parent::ajaxBaseRoute('/ajax/admin/feeds');
        parent::table(new Feed());
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
                'title'=>'Page',
                'id'=>'page.name'
            ],
            [
                'title'=>'Section',
                'id'=>'section.name'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row ? $row->name : ''),
                'page_id' => (new DropDown())
                    ->id('page_id')
                    ->nullable(false)
                    ->label('Page')
                    ->idField('id')
                    ->nameField('name')
                    ->rows(Page::get())
                    ->defaultValue($row ? $row->page_id : ''),
                'section_id' => (new DropDown())
                    ->id('section_id')
                    ->nullable(false)
                    ->label('Section')
                    ->idField('id')
                    ->nameField('name')
                    ->rows(Section::get())
                    ->defaultValue($row ? $row->section_id : '')
            ];
        });
        parent::tableName('feed');
    }

    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
//            (new TemplateCategories())->showAllView()

        ];
    }

    function dataAll(){
        return $this->table->with('page')->with('section')->get();
    }

}