<?php namespace App\AUI\Controllers;


use App\AUI\Model\ItemValue;
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
class TemplateSectionItemValues extends Table2 {

    function __construct(){
        parent::controllerClass('TemplateSectionItemValues');
        parent::headerPlural('Template Section Item Values');
        parent::headerSingular('Template Section Item Value');
        parent::baseRoute('/admin/templates/{id1}/sections/{id2}/items/{id3}/values');
        parent::ajaxBaseRoute('/ajax/admin/templates/{id1}/sections/{id2}/items/{id3}/values');
        parent::table(new ItemValue());
        $this->level=4;
        parent::attributes([
                [
                    'title'=>'ID',
                    'id'=>'id'
                ],
//                [
//                    'title'=>'Item',
//                    'id'=>'item.name'
//                ],
                [
                    'title'=>'Value',
                    'id'=>'value'
                ]
            ]);
        parent::inputs(function($row) {
                $parentId = Request::route("id".($this->level-1));
                return [
                    'item_id' => (new MetaItem())
                        ->id('item_id')
                        ->defaultValue($parentId),
                    'value' => (new TextBox)
                        ->id('value')
                        ->label('value')
                        ->defaultValue($row?$row->value:'')
                ];
        });
        $this->parentHeader='Template Section Items';
        parent::tableName('item_value');
    }

    function dataAll(){
        return $this->table->where("item_id","=",Request::route("id".($this->level-1)))->with('item')->get();
    }

}