<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreProductTypePrice;
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

class StoreProductTypePrices extends Table2 {


    function __construct(){
        parent::controllerClass('StoreProductTypePrices');
        parent::headerPlural('Store Product Type Prices');
        parent::headerSingular('Store Product Type Price');
        parent::baseRoute('/admin/store-types/{id1}/price');
        parent::ajaxBaseRoute('/ajax/admin/users/{id1}/price');
        $this->level=2;
        $this->parentHeader='Store Product Type';
        $this->foreignKeyField='store_product_type_id';
        parent::table(new StoreProductTypePrice());
        parent::tableName('store_product_type_price');
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
                'title'=>'Price',
                'id'=>'price'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row?$row->name:''),
                'price' => (new NumberInput())
                    ->id('price')
                    ->label('Price')
                    ->defaultValue($row?$row->price:'')
            ];

        });
    }


    function dataAll(){
        return $this->table->where('store_product_type_id','=',Request::route('id1'))->get();
    }

}