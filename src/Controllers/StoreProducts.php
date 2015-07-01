<?php namespace App\AUI\Controllers;


use App\AUI\Model\Category;
use App\AUI\Model\Coach;
use App\AUI\Model\Family;
use App\AUI\Model\Role;
use App\AUI\Model\RoleUser;
use App\AUI\Model\StoreProduct;
use App\AUI\Model\StoreProductCategory;
use App\AUI\Model\StoreProductType;
use App\AUI\Model\StoreSelect;
use App\AUI\Model\StoreType;
use App\AUI\Model\Template;
use App\AUI\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Log;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\Checkbox;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\FileInput;
use MJ1618\AdminUI\Form\HiddenInput;
use MJ1618\AdminUI\Form\ImageInput;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\NumberInput;
use MJ1618\AdminUI\Form\PasswordInput;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\Table;

class StoreProducts extends Table2 {


    function __construct(){
        parent::controllerClass('StoreProducts');
        parent::headerPlural('Store Products');
        parent::headerSingular('Store Product');
        parent::baseRoute('/admin/store-products');
        parent::ajaxBaseRoute('/ajax/admin/store-products');
        parent::table(new StoreProduct());
        parent::tableName('store_product');
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
                'title'=>'Pricing Type',
                'id'=>'store_type.name'
            ]
        ]);
        parent::inputs(function($row) {
            return [
                'name' => (new TextBox)
                    ->id('name')
                    ->label('Name')
                    ->defaultValue($row?$row->name:''),
                'short_description' => (new PlainTextAreaBox())
                    ->id('short_description')
                    ->label('Short Description')
                    ->defaultValue($row?$row->short_description:''),
                'description' => (new PlainTextAreaBox())
                    ->id('description')
                    ->label('Description')
                    ->defaultValue($row?$row->description:''),
                'image'=>(new ImageInput())
                    ->id('image')
                    ->label('Image')
                    ->defaultValue($row?$row->image:''),
                'store_type_id' => (new DropDown())
                    ->id('store_type_id')
                    ->nullable(false)
                    ->label('Pricing Type')
                    ->idField('id')
                    ->nameField('name')
                    ->rows(StoreType::all())
                    ->defaultValue($row?$row->store_type_id:''),
                'store_select_id' => (new DropDown())
                    ->id('store_select_id')
                    ->nullable(false)
                    ->label('Select Options')
                    ->idField('id')
                    ->nameField('name')
                    ->rows(StoreSelect::all())
                    ->defaultValue($row?$row->store_select_id:'')
            ];
        });
    }

    function getViewViews(){
        return [
            (new StoreProductSubcategories())->showAllView()
        ];
    }

    function dataAll(){
        return $this->table->with('storeType')->get();
    }

}