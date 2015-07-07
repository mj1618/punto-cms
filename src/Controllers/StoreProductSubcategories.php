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

class StoreProductSubcategories extends Table2 {


    function __construct(){
        parent::controllerClass('StoreProductSubcategories');
        parent::headerPlural('Store Product Sub-Categories');
        parent::headerSingular('Store Product Sub-Category');
        parent::baseRoute('/admin/store-products/{id1}/subcategories');
        parent::ajaxBaseRoute('/ajax/admin/store-products/{id1}/subcategories');
        parent::table(new StoreProductSubcategory());
        parent::tableName('store_product_subcategory');
        $this->level=2;
        $this->parentHeader='Store Product';
        $this->foreignKeyField='store_product_id';
        parent::attributes([
            [
                'title'=>'ID',
                'id'=>'id'
            ],
            [
                'title'=>'Sub-category',
                'id'=>'store_subcategory.name'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'store_product_id'=>(new MetaItem())
                    ->id('store_product_id')
                    ->defaultValue(Request::route('id1')),
                'store_subcategory_id' => (new DropDown())
                    ->id('store_subcategory_id')
                    ->nullable(false)
                    ->label('Sub-category')
                    ->idField('id')
                    ->nameField('name')
                    ->rows(StoreSubcategory::all())
                    ->defaultValue($row?$row->store_subcategory_id:''),
                'description' => (new TextAreaBox())
                    ->id('description')
                    ->label('description')
                    ->defaultValue($row?$row->description:''),
            ];

        });
    }

    function buttons(){
        return [
            "create" => [
                'id'=>$this->getHeaderSingular()."-create",
                'text'=>'Add',
                'requiresSelect'=>'false',
                'url'=>$this->getCreateUrl(),
                'float'=>'left'
            ]
        ];
    }

    function dataAll(){
        return $this->table->with('storeSubcategory')->where('store_product_id','=',Request::route('id1'))->get();
    }

}