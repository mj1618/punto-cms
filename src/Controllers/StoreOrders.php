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
                'email' => (new TextBox())
                    ->id('email')
                    ->label('email')
                    ->defaultValue($row?$row->email:''),
                'payer_id' => (new TextBox())
                    ->id('payer_id')
                    ->label('payer_id')
                    ->defaultValue($row?$row->payer_id:''),
                'payer_status' => (new TextBox())
                    ->id('payer_status')
                    ->label('payer_status')
                    ->defaultValue($row?$row->payer_status:''),
                'first_name' => (new TextBox())
                    ->id('first_name')
                    ->label('first_name')
                    ->defaultValue($row?$row->first_name:''),
                'last_name' => (new TextBox())
                    ->id('last_name')
                    ->label('last_name')
                    ->defaultValue($row?$row->last_name:''),
                'country_code' => (new TextBox())
                    ->id('country_code')
                    ->label('country_code')
                    ->defaultValue($row?$row->country_code:''),
                'ship_to_name' => (new TextBox())
                    ->id('ship_to_name')
                    ->label('ship_to_name')
                    ->defaultValue($row?$row->ship_to_name:''),
                'ship_to_street' => (new TextBox())
                    ->id('ship_to_street')
                    ->label('ship_to_street')
                    ->defaultValue($row?$row->ship_to_street:''),
                'ship_to_city' => (new TextBox())
                    ->id('ship_to_city')
                    ->label('ship_to_city')
                    ->defaultValue($row?$row->ship_to_city:''),
                'ship_to_state' => (new TextBox())
                    ->id('ship_to_state')
                    ->label('ship_to_state')
                    ->defaultValue($row?$row->ship_to_state:''),
                'ship_to_zip' => (new TextBox())
                    ->id('ship_to_zip')
                    ->label('ship_to_zip')
                    ->defaultValue($row?$row->ship_to_zip:''),
                'ship_to_country' => (new TextBox())
                    ->id('ship_to_country')
                    ->label('ship_to_country')
                    ->defaultValue($row?$row->ship_to_country:''),
                'currecncy_code' => (new TextBox())
                    ->id('currecncy_code')
                    ->label('currecncy_code')
                    ->defaultValue($row?$row->currecncy_code:''),
                'note' => (new TextBox())
                    ->id('note')
                    ->label('note')
                    ->defaultValue($row?$row->note:''),
                'shipping' => (new TextBox())
                    ->id('shipping')
                    ->label('Shipping Included')
                    ->defaultValue($row?$row->shipping:''),
                'total' => (new TextBox())
                    ->id('total')
                    ->label('Total')
                    ->defaultValue($row?$row->total:''),

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