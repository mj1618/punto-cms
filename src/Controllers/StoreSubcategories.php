<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreProductSubcategory;
use App\AUI\Model\StoreSubcategory;
use App\AUI\Model\Template;
use App\AUI\Model\User;
use Auth;
use File;
use Input;
use Password;
use Log;
use Request;
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

class StoreSubcategories extends Table2 {


    function __construct(){
        parent::controllerClass('StoreSubcategories');
        parent::headerPlural('Store Sub-Categories');
        parent::headerSingular('Store Sub-Category');
        parent::baseRoute('/admin/store-categories/{id1}');
        parent::ajaxBaseRoute('/ajax/admin/store-categories/{id1}');
        parent::table(new StoreSubcategory());
        parent::tableName('store_subcategory');
        $this->level=2;
        $this->parentHeader='Store Category';
        $this->foreignKeyField='store_category_id';
        parent::attributes([
            [
                'title'=>'Order',
                'id'=>'sort'
            ],
            [
                'title'=>'Name',
                'id'=>'name'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'store_category_id'=>(new MetaItem())
                    ->id('store_category_id')
                    ->defaultValue(Request::route('id1')),
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row?$row->name:''),
                'description' => (new TextAreaBox())
                    ->id('description')
                    ->label('description')
                    ->defaultValue($row?$row->description:'')
            ];

        });
    }


    function buttons(){
        return [
            "view" => [
                'id'=>$this->getHeaderSingular()."-view",
                'text'=>'View/Edit',
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
            "Sort" => [
                'id'=>$this->getHeaderSingular()."-sort",
                'text'=>'Sort',
                'requiresSelect'=>'false',
                'url'=>$this->getBaseUrl()."/sort",
                'float'=>'left'
            ]
        ];
    }


    function dataAll(){
        return $this->table->where('store_category_id','=',Request::route('id1'))->get();
    }

}