<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
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

class Templates extends Table2 {


    function __construct(){
        parent::controllerClass('Templates');
        parent::headerPlural('Templates');
        parent::headerSingular('Template');
        parent::baseRoute('/admin/templates');
        parent::ajaxBaseRoute('/ajax/admin/templates');
        parent::table(new Template());
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
                'title'=>'File',
                'id'=>'filename'
            ]
        ]);
        parent::inputs(function($row) {

            $path = base_path().'\resources\views\aui\templates';

            $fs = scandir($path);
            $fs = array_filter( $fs, function($fn){
                return ends_with($fn, '.blade.php');
            });
            $fs = array_map(function($fn){
                return ['filename'=>$fn];
            }, $fs);


//            Log::info($fs);

            return [
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row?$row->name:''),
                'description' => (new PlainTextAreaBox())
                    ->id('description')
                    ->label('Description')
                    ->defaultValue($row?$row->description:''),
                'file' => (new DropDown())
                    ->id('filename')
                    ->nullable(false)
                    ->label('Filename')
                    ->idField('filename')
                    ->nameField('filename')
                    ->rows($fs)
                    ->defaultValue($row?$row->filename:'')
            ];

        });
        parent::tableName('template');
    }

    function buttons(){
        return [
            "edit" => [
                'id'=>$this->getHeaderSingular()."-edit",
                'text'=>'Edit',
                'requiresSelect'=>'true',
                'url'=>$this->getEditPartialRoute()
            ],
            "view" => [
                'id'=>$this->getHeaderSingular()."-view",
                'text'=>'View',
                'requiresSelect'=>'true',
                'url'=>$this->getViewPartialRoute()
            ],
            "create" => [
                'id'=>$this->getHeaderSingular()."-create",
                'text'=>'Create',
                'requiresSelect'=>'false',
                'url'=>$this->getCreateUrl(),
                'float'=>'left'
            ],
            "develop" => [
                'id'=>$this->getHeaderSingular()."-develop",
                'text'=>'Edit Code',
                'requiresSelect'=>'true',
                'url'=>"/admin/template/{id}/develop"
            ]
        ];
    }

    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
            (new TemplateSections())->showAllView()

        ];
    }

    function dataAll(){
        return $this->table->get();
    }

}