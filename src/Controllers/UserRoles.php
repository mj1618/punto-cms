<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\ChildTable;
use Request;
class UserRoles extends Table2 {

    function __construct(){
        parent::controllerClass('UserRoles');
        parent::headerPlural('User Roles');
        parent::headerSingular('User Role');
        parent::baseRoute('/admin/users/{id1}/roles');
        parent::ajaxBaseRoute('/ajax/admin/users/{id1}/roles');
        parent::table(new RoleUser());
        $this->level=2;
        parent::attributes([
            [
                'title'=>'User',
                'id' => 'user.username'
            ],
            [
                'title'=>'Role',
                'id' => 'role.name'
            ]
        ]);
        parent::inputs(function($row) {
            $parentId = Request::route("id".($this->level-1));
            return [
                'user_id' => (new MetaItem())
                    ->id('user_id')
                    ->defaultValue($parentId),
                'role'=>(new DropDown())
                    ->id('role_id')
                    ->nullable(false)
                    ->idField('id')
                    ->nameField('name')
                    ->defaultValue($row?$row->role_id:'')
                    ->label('Role')
                    ->rows(Role::get())
            ];
        });
        $this->parentHeader='User';
        parent::tableName('role_user');
    }
    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
//            (new TemplateCategoryItems())->showAllView()

        ];
    }

    function dataAll(){
        $parentId = Request::route("id".($this->level-1));
        return array_map(function($ru){
            $ru["id"]=$ru["role_id"];
            return $ru;
        },RoleUser::where('user_id','=',$parentId)->with('user')->with('role')->get()->toArray());
    }

    function ajaxDelete(){
        $parentId = Request::route("id".($this->level-1));
        $id = Request::route("id".($this->level));
        $this->table->where('role_id','=',$id)->where('user_id','=',$parentId)->delete();
        return \Response::json('success');
    }

    function getSingleItem(){
        $parentId = Request::route("id".($this->level-1));
        $id = Request::route("id".($this->level));
        return $this->table->where('role_id','=',$id)->where('user_id','=',$parentId)->get()->first();
    }

}