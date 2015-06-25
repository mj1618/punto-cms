<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreProductCategory;
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

class StoreProductCategories extends Table2 {


    function __construct(){
        parent::controllerClass('StoreProductCategories');
        parent::headerPlural('Store Product Categories');
        parent::headerSingular('Store Product Category');
        parent::baseRoute('/admin/store-categories');
        parent::ajaxBaseRoute('/ajax/admin/store-categories');
        parent::table(new StoreProductCategory());
        parent::tableName('store_product_category');
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
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row?$row->name:'')
            ];

        });
    }

    function getViewViews(){
        return [
            (new StoreProductSubcategories())->showAllView()
        ];
    }

    function dataAll(){
        return $this->table->get();
    }

}