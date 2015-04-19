<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\Section;
use App\AUI\Model\Template;
use App\AUI\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Log;
use MJ1618\AdminUI\Form\Checkbox;
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

class Sections extends Table {


    function __construct(){
        parent::controllerClass('Sections');
        parent::headerPlural('Sections');
        parent::headerSingular('Section');
        parent::baseRoute('/admin/sections');
        parent::ajaxBaseRoute('/ajax/admin/sections');
        parent::table(new Section());
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
                'title'=>'Template',
                'id'=>'template.name'
            ],
            [
                'title'=>'Single Post',
                'id'=>'single'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row?$row->name:''),
                'description' => (new PlainTextAreaBox())
                    ->id('description')
                    ->label('Description')
                    ->defaultValue($row?$row->description:''),
                'template_id' => (new DropDown())
                    ->id('template_id')
                    ->nullable(false)
                    ->label('Template')
                    ->idField('id')
                    ->nameField('name')
                    ->rows(Template::get())
                    ->defaultValue($row?$row->template_id:''),
                'single'=>(new Checkbox())
                    ->id('single')
                    ->label('Single Post?')
                    ->defaultValue($row?$row->single:'')
            ];

        });
        parent::tableName('section');
    }

    function showView($id,$views=[]){
        return parent::showView($id,[
//            (new UserRoles())->showAllView($id)
        ]);
    }

    function dataAll(){
        return $this->table->with('template')->get();
    }

}