<?php namespace App\AUI\Controllers;


use App\AUI\Model\Section;
use App\AUI\Model\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\Checkbox;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\ChildTable;
use Request;
class TemplateSections extends Table2 {

    function __construct(){
        parent::controllerClass('TemplateSections');
        parent::headerPlural('Template Sections');
        parent::headerSingular('Template Section');
        parent::baseRoute('/admin/templates/{id1}/sections');
        parent::ajaxBaseRoute('/ajax/admin/templates/{id1}/sections');
        parent::table(new Section());
        $this->level=2;
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
                'title'=>'Single Post?',
                'id'=>'single_formatted'
            ],
            [
                'title'=>'Has Attachments?',
                'id'=>'has_attachments_formatted'
            ]
            ]);
        parent::inputs(function($row) {
                $parentId = Request::route("id".($this->level-1));
                return [
                    'template_id' => (new MetaItem())
                        ->id('template_id')
                        ->defaultValue($parentId),
                    'name' => (new TextBox)
                        ->id('name')
                        ->label('Name')
                        ->defaultValue($row?$row->name:''),
                    'description' => (new PlainTextAreaBox())
                        ->id('description')
                        ->label('Description')
                        ->defaultValue($row?$row->description:''),
                    'single'=>(new Checkbox())
                        ->id('single')
                        ->label('Single Post?')
                        ->defaultValue($row?$row->single:''),
                    'has_attachments'=>(new Checkbox())
                        ->id('has_attachments')
                        ->label('Has Attachments?')
                        ->defaultValue($row?$row->has_attachments:'')
                ];
        });
        $this->parentHeader='Template';
        $this->foreignKeyField="template_id";
        parent::tableName('section');
    }
    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
            (new TemplateSectionItems())->showAllView()

        ];
    }
}