<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreOrderProduct;
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

class StoreOrderProducts extends Table2 {


    function __construct(){
        parent::controllerClass('StoreOrderProducts');
        parent::headerPlural('Store Order Products');
        parent::headerSingular('Store Order Product');
        parent::baseRoute('/admin/store-orders/{id1}/products');
        parent::ajaxBaseRoute('/ajax/admin/store-orders/{id1}/products');
        parent::table(new StoreOrderProduct());
        parent::tableName('store_order_product');
        $this->level=2;
        $this->parentHeader='Store Order';
        $this->foreignKeyField='store_order_id';
        $this->useDeleteButton = false;
        parent::attributes([
            [
                'title'=>'Option',
                'id'=>'select'
            ],
            [
                'title'=>'Product',
                'id'=>'product'
            ],
            [
                'title'=>'Type',
                'id'=>'type_price'
            ],
            [
                'title'=>'Quantity',
                'id'=>'quantity'
            ],
            [
                'title'=>'Total',
                'id'=>'price'
            ]
        ]);
        parent::inputs(function($row) {
            return [
            ];

        });
    }

    function buttons(){
        return [
        ];
    }

    function dataAll(){
        return $this->table->where('store_order_id','=',Request::route('id1'))->get();
    }

}