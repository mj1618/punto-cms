<?php namespace App\AUI\Controllers;


use App\AUI\Model\Post;
use App\AUI\Model\Section;
use App\AUI\Model\Page;
use App\AUI\Model\PageSection;
use App\AUI\Model\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use MJ1618\AdminUI\Controller\Table2;
use MJ1618\AdminUI\Form\DropDown;
use MJ1618\AdminUI\Form\MetaItem;
use MJ1618\AdminUI\Form\PlainTextAreaBox;
use MJ1618\AdminUI\Form\TextAreaBox;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Utils\ViewUtils;
use MJ1618\AdminUI\Controller\ChildTable;
use Request;
class EditPagesSections extends Table2 {

    function __construct(){
        parent::controllerClass('EditPagesSections');
        parent::headerPlural('Page Sections');
        parent::headerSingular('Page Section');
        parent::baseRoute('/admin/edit-pages/{id1}/sections');
        parent::ajaxBaseRoute('/ajax/admin/edit-pages/{id1}/sections');
        parent::table(new Post());
        $this->level=2;
        $this->useEditButton=false;
        parent::attributes([
                [
                    'title'=>'ID',
                    'id'=>'id'
                ],
                [
                    'title'=>'Section Name',
                    'id'=>'name'
                ],
                [
                    'title'=>'Category',
                    'id'=>'section.name'
                ]
            ]);
        parent::inputs(function($row) {
                $parentId = Request::route("id".($this->level-1));
                $page = Page::find($parentId);

                return [
                    'page_id' => (new MetaItem())
                        ->id('page_id')
                        ->defaultValue($parentId),
                    'name' => (new TextBox)
                        ->id('name')
                        ->label('Name')
                        ->defaultValue($row?$row->name:''),
                    'description' => (new PlainTextAreaBox())
                        ->id('description')
                        ->label('Description')
                        ->defaultValue($row?$row->description:''),
                    'section_id' => (new DropDown())
                        ->id('section_id')
                        ->nullable(false)
                        ->label('Category')
                        ->idField('id')
                        ->nameField('name')
                        ->rows(Section::where('single','=',1)->where('template_id','=',$page->template()->first()->id)->get())
                        ->defaultValue($row?$row->section_id:'')
                ];
        });
        $this->parentHeader='Edit Page';
        parent::tableName('post');
    }


    function buttons(){
        return [
            "view" => [
                'id'=>$this->getHeaderSingular()."-view",
                'text'=>'Edit Section',
                'requiresSelect'=>'true',
                'url'=>$this->getViewPartialRoute()
            ],
            "create" => [
                'id'=>$this->getHeaderSingular()."-create",
                'text'=>'Create Section',
                'requiresSelect'=>'false',
                'url'=>$this->getCreateUrl(),
                'float'=>'left'
            ]
        ];
    }

    function getViewViews(){
        return [
//            (new UserRoles())->showAllView($id)
//            (new TemplateSectionItems())->showAllView()
            (new PageContentForm())->showViewBox()

        ];
    }

    function dataAll(){
        return $this->table->where('page_id','=',Request::route('id1'))->whereIn('section_id',Section::where('single','=',1)->lists('id'))->with('section')->get();
    }
}