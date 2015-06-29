<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreProductSubcategory;
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

class StoreProductSubcategories extends Table2 {


    function __construct(){
        parent::controllerClass('StoreProductSubcategories');
        parent::headerPlural('Store Product Sub-Categories');
        parent::headerSingular('Store Product Sub-Category');
        parent::baseRoute('/admin/store-categories/{id1}');
        parent::ajaxBaseRoute('/ajax/admin/store-categories/{id1}');
        parent::table(new StoreProductSubcategory());
        parent::tableName('store_product_subcategory');
        $this->level=2;
        $this->parentHeader='Store Product Category';
        $this->foreignKeyField='store_product_category_id';
        parent::attributes([
            [
                'title'=>'ID',
                'id'=>'id'
            ],
            [
                'title'=>'Name',
                'id'=>'name'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'store_product_category_id'=>(new MetaItem())
                    ->id('store_product_category_id')
                    ->defaultValue(Request::route('id1')),
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row?$row->name:'')
            ];

        });
    }


    function dataAll(){
        return $this->table->where('store_product_category_id','=',Request::route('id1'))->get();
    }

}