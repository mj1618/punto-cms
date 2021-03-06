<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Page extends Model {

    protected $table = 'page';

    function template(){
        return $this->belongsTo('App\AUI\Model\Template');
    }
    function language(){
        return $this->belongsTo('App\AUI\Model\Language');
    }
    function posts(){
        return $this->hasMany('App\AUI\Model\Post');
    }

    function findPost($name){
        return Post::where('page_id','=',$this->id)->where('name','=',$name)->get()->first();
    }


    function findPostById($id){
        Log::info('####'.$id);
        return Post::where('page_id','=',$this->id)->where('id','=',$id)->get()->first();
    }

    function findPosts($name){

        $ps = Post::where('page_id','=',$this->id)->whereIn('section_id',Section::where('name','=',$name)->lists('id'))->orderBy('sort','ASC')->orderBy('id','ASC')->get();

        $posts = [];
        foreach($ps as $p){
            $posts[] = $p;
        }

        return $posts;
    }
    function countPosts($name){
        return Post::where('page_id','=',$this->id)->whereIn('section_id',Section::where('name','=',$name)->lists('id'))->count();
    }

    function copy($name, $desc, $url){
        $page = new Page();

        $page->name = $name;
        $page->description = $desc;
        $page->url = $url;
        $page->template_id = $this->template_id;
        $page->save();

        foreach($this->posts()->get() as $oldPost){

            $post = new Post();
            $post->page_id=$page->id;
            $post->section_id=$oldPost->section_id;
            $post->name=$oldPost->name;
            $post->save();

            foreach($oldPost->contents()->get() as $oldContent){

                $content = new Content();
                $content->value = $oldContent->value;
                $content->post_id = $post->id;
                $content->item_id=$oldContent->item_id;
                $content->save();
            }

            foreach($oldPost->attachments()->get() as $oldAttachment){

                $attachment = new Attachment();
                $attachment->value = $oldAttachment->value;
                $attachment->name = $oldAttachment->name;
                $attachment->post_id = $post->id;
                $attachment->item_type_id=$oldAttachment->item_type_id;
                $attachment->save();
            }

        }

        return $page;
    }
}
