<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreCategory;
use App\AUI\Model\StoreOrder;
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

class StoreOrders extends Table2 {


    function __construct(){
        parent::controllerClass('StoreOrders');
        parent::headerPlural('Store Orders');
        parent::headerSingular('Store Order');
        parent::baseRoute('/admin/store-orders');
        parent::ajaxBaseRoute('/ajax/admin/store-orders');
        parent::table(new StoreOrder());
        parent::tableName('store_order');
        parent::attributes([
            [
                'title'=>'Created',
                'id'=>'created_at'
            ],
            [
                'title'=>'Total',
                'id'=>'total'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'fields' => (new PlainTextAreaBox())
                    ->id('fields')
                    ->label('Details')
                    ->defaultValue($row?$row->fields:''),
                'total' => (new TextBox())
                    ->id('total')
                    ->label('Total')
                    ->defaultValue($row?$row->total:''),
                'shipping' => (new TextBox())
                    ->id('shipping')
                    ->label('Shipping Included')
                    ->defaultValue($row?$row->shipping:''),

            ];

        });
    }

    function getViewViews(){
        return [
            (new StoreOrderProducts())->showAllView()
        ];
    }

    function dataAll(){
        return $this->table->get();
    }

}