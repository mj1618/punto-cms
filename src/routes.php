<?php namespace App\AUI;
/**
 * Created by PhpStorm.
 * User: MattUpstairs
 * Date: 17/03/2015
 * Time: 1:08 AM
 */


use App\AUI\Controllers\AdminLogin;
use App\AUI\Controllers\Base64Image;
use App\AUI\Controllers\DevelopTemplateForm;
use App\AUI\Controllers\EditPagesAttachments;
use App\AUI\Controllers\EditPagesPosts;
use App\AUI\Controllers\FeedRoute;
use App\AUI\Controllers\Feeds;
use App\AUI\Controllers\PageCopyForm;
use App\AUI\Controllers\PagePreview;
use App\AUI\Controllers\PagesList;
use App\AUI\Controllers\PageSummary;
use App\AUI\Controllers\PageSummaryAttachments;
use App\AUI\Controllers\PageSummaryAttachmentsDeleteForm;
use App\AUI\Controllers\PageSummaryNewAttachmentForm;
use App\AUI\Controllers\PageSummaryPosts;
use App\AUI\Controllers\PageSummaryPostsCreateForm;
use App\AUI\Controllers\PageSummaryPostsDeleteForm;
use App\AUI\Controllers\PageSummaryPostsForm;
use App\AUI\Controllers\Sections;
use App\AUI\Controllers\DevelopTemplates;
use App\AUI\Controllers\EditPages;
use App\AUI\Controllers\EditPagesSections;
use App\AUI\Controllers\PageRoute;
use App\AUI\Controllers\Pages;
use App\AUI\Controllers\RootUrl;
use App\AUI\Controllers\TemplateSections;
use App\AUI\Controllers\TemplateSectionItems;
use App\AUI\Controllers\Templates;
use App\AUI\Controllers\UserPages;
use App\AUI\Controllers\UserRoles;
use App\AUI\Controllers\Users;
use App\AUI\Controllers\PageContentForm;
use App\AUI\Model\Page;
use Illuminate\Support\Facades\Route;
use Debugbar;
use Log;
\Debugbar::disable();

(new AdminLogin())->routes();

(new PageRoute())->routes();
(new FeedRoute())->routes();

Route::group(['middleware' => ['adminauth']], function(){
    (new Users())->routes();
    (new Users())->breadcrumbs();
    (new UserRoles())->routes();
    (new UserRoles())->breadcrumbs();
    (new UserPages())->routes();
    (new UserPages())->breadcrumbs();
});


Route::group(['middleware' => ['editorauth']], function(){
    (new EditPages())->routes();
    (new EditPages())->breadcrumbs();
    (new PageContentForm())->routes();
    (new PageContentForm())->breadcrumbs();
    (new EditPagesAttachments())->routes();
    (new EditPagesAttachments())->breadcrumbs();
    (new EditPagesPosts())->routes();
    (new EditPagesPosts())->breadcrumbs();
    (new EditPagesSections())->routes();
    (new EditPagesSections())->breadcrumbs();
    (new PagePreview())->routes();
    (new PageCopyForm())->routes();

    (new Feeds())->routes();
    (new Feeds())->breadcrumbs();

    (new PagesList())->routes();
    (new PagesList())->breadcrumbs();
    (new PageSummary())->routes();
//    (new PageSummary())->breadcrumbs();

    (new PageSummaryPostsForm())->routes();
    (new PageSummaryPostsForm())->breadcrumbs();
    (new PageSummaryPostsDeleteForm())->routes();
    (new PageSummaryPostsDeleteForm())->breadcrumbs();

    (new PageSummaryAttachments())->routes();
    (new PageSummaryAttachments())->breadcrumbs();

    (new PageSummaryAttachmentsDeleteForm())->routes();

    (new PageSummaryPostsCreateForm())->routes();

//    (new PageSummaryNewAttachmentForm())->routes();
//    (new PageSummaryNewAttachmentForm())->breadcrumbs();


});

Route::group(['middleware' => ['developerauth']], function(){
    (new TemplateSectionItems())->routes();
    (new TemplateSectionItems())->breadcrumbs();
    (new Sections())->routes();
    (new Sections())->breadcrumbs();
    (new Templates())->routes();
    (new Templates())->breadcrumbs();
    (new TemplateSections())->routes();
    (new TemplateSections())->breadcrumbs();
    (new Pages())->routes();
    (new Pages())->breadcrumbs();
    (new DevelopTemplateForm())->routes();
});

//
//$routeCollection = Route::getRoutes();

//foreach ($routeCollection as $value) {
//    Log::error( 'r:'.$value->getPath());
//}
//Log::info('count:'.count($routeCollection));