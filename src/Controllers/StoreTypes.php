<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreProductType;
use App\AUI\Model\StoreType;
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

class StoreTypes extends Table2 {


    function __construct(){
        parent::controllerClass('StoreTypes');
        parent::headerPlural('Store Types');
        parent::headerSingular('Store Type');
        parent::baseRoute('/admin/store-types');
        parent::ajaxBaseRoute('/ajax/admin/store-types');
        parent::table(new StoreType());
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
                    ->defaultValue($row?$row->name:''),
                'description' => (new PlainTextAreaBox())
                    ->id('description')
                    ->label('Description')
                    ->defaultValue($row?$row->description:'')
            ];
        });
        parent::tableName('store_type');
    }

    function getViewViews(){
        return [
            (new StoreTypePrices())->showAllView()
        ];
    }

    function dataAll(){
        return $this->table->get();
    }

}