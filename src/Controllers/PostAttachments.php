<?php namespace App\AUI\Controllers;


use App\AUI\Model\Attachment;
use App\AUI\Model\Post;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use MJ1618\AdminUI\Controller\BasicTable;
use MJ1618\AdminUI\Form\ButtonItem;
use MJ1618\AdminUI\Form\NumberInput;
use MJ1618\AdminUI\Form\TextBox;
use MJ1618\AdminUI\Controller\Table;
use Request;
use Route;
class PostAttachments extends BasicTable {

    var $postId;

    function __construct($postId){
        $this->controllerClass='PostAttachments';
        $this->route='/admin/manage-pages/{id1}/posts/{id2}/attachments';
        $this->postId=$postId;
    }

    function header(){
        return '';
    }



    function definition(){
        if($this->postId===null)
            $this->postId= Request::route('id2');
        return $this->def($this->postId);
    }

    function def($postId){
        $this->firstRow();
        $this->addCell('#');
        $this->addCell('Name');
        $this->addCell('Actions');
        $this->nextRow();

        $pageId= Request::route('id1');
        $post = Post::find($postId);
        foreach($post->attachments()->get() as $att){
            $this->addCell('1. ');
            $this->addCell($att->name);
            $this->addCellViews([
                (new ButtonItem())->cssClass("btn-default btn-sm")->target('_blank')->label('Download')->defaultValue("/admin/manage-pages/".$pageId."/posts/".$post->id."/attachments/$att->id/download"),
                (new ButtonItem())->cssClass("btn-default btn-sm")->label('Edit')->defaultValue("/admin/manage-pages/".$pageId."/posts/".$post->id."/attachments/$att->id/edit"),
                (new ButtonItem())->cssClass("btn-default btn-sm")->label('Delete')->defaultValue("/admin/manage-pages/".$pageId."/posts/".$post->id."/attachments/$att->id/delete")
            ]);
//            $this->addCellView();
            $this->nextRow();
        }

        return parent::definition();
    }
}

