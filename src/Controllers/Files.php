<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\File;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\Template;
use App\AUI\Model\User;
use Auth;
use Input;
use MJ1618\AdminUI\Form\FileInput;
use Password;
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

class Files extends Table2 {


    function __construct(){
        parent::controllerClass('Files');
        parent::headerPlural('Files');
        parent::headerSingular('File');
        parent::baseRoute('/admin/files');
        parent::ajaxBaseRoute('/ajax/admin/files');
        parent::table(new File());
        $this->level=2;
        parent::tableName('file');
        parent::attributes([
            [
                'title'=>'ID',
                'id'=>'id'
            ],
            [
                'title'=>'User',
                'id'=>'user.username'
            ],
            [
                'title'=>'Filename',
                'id'=>'filename'
            ],
            [
                'title'=>'URI',
                'id'=>'value'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'user_id'=>(new MetaItem())
                    ->id('user_id')
                    ->defaultValue(Auth::user()->id),
                (new FileInput())
                    ->id('filename')
                    ->valueField('value')
                    ->filenameField('filename')
                    ->label('File '.($row?'- '.$row->filename:''))
                    ->filename($row?$row->value:'')
                    ->defaultValue($row?$row->value:'')
            ];

        });
    }

    function getViewViews(){
        return [
//            (new UserRoles())->showAllView()
        ];
    }

    function dataAll(){
        return $this->table->with('user')->get();
    }

}