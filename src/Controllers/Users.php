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

class Users extends Table2 {


    function __construct(){
        parent::controllerClass('Users');
        parent::headerPlural('Users');
        parent::headerSingular('User');
        parent::baseRoute('/admin/users');
        parent::ajaxBaseRoute('/ajax/admin/users');
        parent::table(new User());
        parent::attributes([
            [
                'title'=>'ID',
                'id'=>'id'
            ],
            [
                'title'=>'Username',
                'id'=>'username'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'username' => (new TextBox)
                    ->id('username')
                    ->label('Username')
                    ->defaultValue($row?$row->username:'')
            ];

        });
        parent::tableName('user');
    }

    function getViewViews(){
        return [
            (new UserRoles())->showAllView(),
            (new UserPages())->showAllView()
        ];
    }

    function dataAll(){
        return $this->table->get();
    }

}