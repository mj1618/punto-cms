<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Page;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\UserPage;
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
class UserPages extends Table2 {

    function __construct(){
        parent::controllerClass('UserPages');
        parent::headerPlural('User Pages');
        parent::headerSingular('User Page');
        parent::baseRoute('/admin/users/{id1}/pages');
        parent::ajaxBaseRoute('/ajax/admin/users/{id1}/pages');
        parent::table(new UserPage());
        $this->level=2;
        parent::attributes([
            [
                'title'=>'User',
                'id' => 'user.username'
            ],
            [
                'title'=>'Page',
                'id' => 'page.name'
            ]
        ]);
        parent::inputs(function($row) {
            $parentId = Request::route("id".($this->level-1));
            return [
                'user_id' => (new MetaItem())
                    ->id('user_id')
                    ->defaultValue($parentId),
                'page_id'=>(new DropDown())
                    ->id('page_id')
                    ->nullable(false)
                    ->idField('id')
                    ->nameField('name')
                    ->defaultValue($row?$row->page_id:'')
                    ->label('Page')
                    ->rows(Page::get())
            ];
        });
        $this->parentHeader='User';
        parent::tableName('user_page');
    }
    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
//            (new TemplateCategoryItems())->showAllView()

        ];
    }

    function dataAll(){
        return $this->table->where("user_id","=",Request::route("id".($this->level-1)))->with('user')->with('page')->get();
    }
//
//    function ajaxDelete(){
//        $parentId = Request::route("id".($this->level-1));
//        $id = Request::route("id".($this->level));
//        $this->table->where('role_id','=',$id)->where('user_id','=',$parentId)->delete();
//        return \Response::json('success');
//    }
//
//    function getSingleItem(){
//        $parentId = Request::route("id".($this->level-1));
//        $id = Request::route("id".($this->level));
//        return $this->table->where('role_id','=',$id)->where('user_id','=',$parentId)->get()->first();
//    }

}