<?php namespace App\AUI\Controllers;


use App\AUI\Model\Section;
use App\AUI\Model\Item;
use App\AUI\Model\ItemType;
use App\AUI\Model\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\ChildTable;
use Request;
class TemplateSectionItems extends Table2 {

    function __construct(){
        parent::controllerClass('TemplateSectionItems');
        parent::headerPlural('Template Section Items');
        parent::headerSingular('Template Section Items');
        parent::baseRoute('/admin/templates/{id1}/sections/{id2}/items');
        parent::ajaxBaseRoute('/ajax/admin/templates/{id1}/sections/{id2}/items');
        parent::table(new Item());
        $this->level=3;
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
                    'title'=>'Type',
                    'id'=>'item_type.name'
                ]
            ]);
        parent::inputs(function($row) {
                $parentId = Request::route("id".($this->level-1));
                return [
                    'section_id' => (new MetaItem())
                        ->id('section_id')
                        ->defaultValue($parentId),
                    'name' => (new TextBox)
                        ->id('name')
                        ->label('Name')
                        ->defaultValue($row?$row->name:''),
                    'item_type_id' => (new DropDown())
                        ->id('item_type_id')
                        ->nullable(false)
                        ->label('Type')
                        ->idField('id')
                        ->nameField('name')
                        ->rows(ItemType::get())
                        ->defaultValue($row?$row->item_type_id:'')
                ];
        });
        $this->parentHeader='Template Section';
        parent::tableName('item');
    }

    function dataAll(){
        return $this->table->where("section_id","=",Request::route("id".($this->level-1)))->with('itemType')->with('section')->get();
    }

}