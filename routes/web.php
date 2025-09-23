<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController; //login

use App\Http\Controllers\Directory\DirectoryBackendController; //list
use App\Http\Controllers\Directory\DirectoryDataController; //list

use App\Http\Controllers\Articles\ArticlesBackendController; //Texteditor
use App\Http\Controllers\Articles\ArticlesDataController; //Texteditor

use App\Http\Controllers\Personnel\PersonnelBackendController; //Personnel
use App\Http\Controllers\Personnel\PersonnelDataController; //Personnel

use App\Http\Controllers\Banner\BannerBackendController; //Banner
use App\Http\Controllers\Banner\BannerDataController; //Banner

use App\Http\Controllers\Slide\SlideBackendController; //Slide
use App\Http\Controllers\Slide\SlideDataController; //Slide

use App\Http\Controllers\ServiceRequest\ServiceBackendController; //Service
use App\Http\Controllers\ServiceRequest\ServiceDataController; //Service

use App\Http\Controllers\Webboard\WebboardBackendController; //Webboard
use App\Http\Controllers\Webboard\WebboardDataController; //Webboard

use App\Http\Controllers\PublicRelations\PublicBackendController;
use App\Http\Controllers\PublicRelations\PublicDataController;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

//login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//backend
Route::middleware(['auth'])->group(function () {

    Route::get('backend', [DashboardController::class, 'backend'])->name('backend');
    //list
    Route::get('backend/directory/menu/{menu}', [DirectoryBackendController::class, 'SelectDirectory'])->name('directory');
    Route::get('backend/addDirectory/menu/{menu}', [DirectoryBackendController::class, 'FormAdd'])->name('directory.add');
    Route::post('backend/addDirectory/menu/{menu}', [DirectoryBackendController::class, 'Insert'])->name('directory.insert');
    Route::get('backend/updateDirectory/menu/{menu}/id/{id}', [DirectoryBackendController::class, 'FormEdit'])->name('directory.edit');
    Route::put('backend/updateDirectory/menu/{menu}/id/{id}', [DirectoryBackendController::class, 'Edit'])->name('directory.update');
    Route::get('backend/deleteDirectoryid/menu/{menu}/id/{id}', [DirectoryBackendController::class, 'Delete'])->name('directory.delete');
    Route::get('backend/deleteDirectoryfile/menu/{menu}/id/{id}/idfile/{idfile}', [DirectoryBackendController::class, 'DeleteOneFile'])->name('directory.deleteOnefile');
    Route::post('/file/toggle-visibility', [DirectoryBackendController::class, 'toggleFileVisibility'])->name('directory.toggleFileVisibility');

    //category
    Route::get('backend/directory/menu/{menu}/cate/{cate}', [DirectoryBackendController::class, 'SelectCategory'])->name('directory.category');
    Route::get('backend/categorylist/menu/{menu}', [DirectoryBackendController::class, 'SelectCategorylist']);
    Route::get('backend/addDirectory/menu/{menu}/cate/{cate}', [DirectoryBackendController::class, 'FormAdd'])->name('directory.add.datacat');
    Route::post('backend/addDirectory/menu/{menu}/cate/{cate}', [DirectoryBackendController::class, 'Insert'])->name('directory.insert.datacat');
    Route::get('backend/updateDirectory/menu/{menu}/id/{id}/cate/{cate}', [DirectoryBackendController::class, 'FormEdit'])->name('directory.edit.datacat');
    Route::put('backend/updateDirectory/menu/{menu}/id/{id}/cate/{cate}', [DirectoryBackendController::class, 'Edit'])->name('directory.update.datacat');
    Route::get('backend/addDirectoryCategory/menu/{menu}', [DirectoryBackendController::class, 'FormAddCategory'])->name('directory.category.add');
    Route::post('backend/addDirectoryCategory/menu/{menu}', [DirectoryBackendController::class, 'InsertCategory'])->name('directory.category.insert');
    Route::get('backend/updateDirectoryCategory/menu/{menu}/id/{id}', [DirectoryBackendController::class, 'FormEditCategory'])->name('directory.category.edit');
    Route::put('backend/updateDirectoryCategory/menu/{menu}/id/{id}', [DirectoryBackendController::class, 'EditCategory'])->name('directory.category.update');
    Route::get('backend/deletecategoryid/menu/{menu}/id/{id}', [DirectoryBackendController::class, 'DeleteCategoryid'])->name('directory.delete.cate');
    Route::get('backend/deleteDirectoryfile/menu/{menu}/id/{id}/idfile/{idfile}/cate/{cate}', [DirectoryBackendController::class, 'DeleteOneFile'])->name('directory.deleteOnefile.category');

    //Texteditor
    Route::get('backend/articles/menu/{menu}', [ArticlesBackendController::class, 'SelectArticles'])->name('articles.data');
    Route::post('backend/articles/menu/{menu}', [ArticlesBackendController::class, 'InsertArticles'])->name('articles.insert');
    Route::get('backend/deletearticlesfile/menu/{menu}/id/{id}', [ArticlesBackendController::class, 'DeleteAarticlesfile'])->name('articles.deletefile');

    //Personnel
    Route::get('backend/personnel/menu/{menu}', [PersonnelBackendController::class, 'SelectDataPersonnel'])->name('selectpersonnel');
    Route::get('backend/addpersonnel/menu/{menu}', [PersonnelBackendController::class, 'add']);
    Route::post('backend/addpersonnel/menu/{menu}', [PersonnelBackendController::class, 'insertpersonnel'])->name('personnel.insert');
    Route::get('backend/editpersonnel/menu/{menu}/id/{id}', [PersonnelBackendController::class, 'selectpersonnelid'])->name('personnel.edit');
    Route::post('backend/editpersonnel/menu/{menu}/id/{id}', [PersonnelBackendController::class, 'editpersonnel'])->name('editpersonnelone');
    Route::get('backend/deletepersonnel/menu/{menu}/id/{id}', [PersonnelBackendController::class, 'deletepersonnel'])->name('deletepersonnelid');
    Route::get('backend/personnelseq/menu/{menu}', [PersonnelBackendController::class, 'selectdataseq']);
    Route::post('backend/personnelseq/menu/{menu}', [PersonnelBackendController::class, 'updateseqpersonnel'])->name('updateseqpersonnel');

    //banner
    Route::get('backend/banner/menu/{menu}', [BannerBackendController::class, 'SelectBanner'])->name('selectbanner');
    Route::get('backend/addbanner/menu/{menu}', [BannerBackendController::class, 'AddBanner'])->name('addbanner');
    Route::post('backend/addbanner/menu/{menu}', [BannerBackendController::class, 'InsertBanner'])->name('banner.insert');
    Route::get('backend/editbanner/menu/{menu}/id/{id}', [BannerBackendController::class, 'SelectBannerOne'])->name('bannerone');
    Route::post('backend/editbanner/menu/{menu}/id/{id}', [BannerBackendController::class, 'EditBanner'])->name('editbannerone');
    Route::get('backend/deletebanner/menu/{menu}/id/{id}', [BannerBackendController::class, 'DeleteBanner'])->name('deletebannerone');

    //slide
    Route::get('backend/slide/menu/{menu}', [SlideBackendController::class, 'selectslide'])->name('selectslide');
    Route::get('backend/addslide/menu/{menu}', [SlideBackendController::class, 'addslide'])->name('addslide');
    Route::post('backend/addslide/menu/{menu}', [SlideBackendController::class, 'insertslide'])->name('slide.insert');
    Route::get('backend/editslide/menu/{menu}/id/{id}', [SlideBackendController::class, 'selectslideone'])->name('slideone');
    Route::post('backend/editslide/menu/{menu}/id/{id}', [SlideBackendController::class, 'editslide'])->name('editslideone');
    Route::get('backend/deletesilde/menu/{menu}/id/{id}', [SlideBackendController::class, 'deleteslide'])->name('deleteslideid');

    Route::post('backend/addslidevideo/menu/{menu}', [SlideBackendController::class, 'insertslidevideo'])->name('slide.insert.video');
    Route::post('backend/editslidevideo/menu/{menu}/id/{id}', [SlideBackendController::class, 'editslidevideo'])->name('editslideone.video');

    // complaint
    Route::get('backend/complaint/menu/{menu}', [ServiceBackendController::class, 'SelectComplaint'])->name('list.complaint');
    Route::get('backend/complaintdetail/menu/{menu}/id/{id}', [ServiceBackendController::class, 'ComplaintDetail'])->name('complaintdetail');
    Route::post('backend/complaintdetail/menu/{menu}/id/{id}', [ServiceBackendController::class, 'ComplaintUpdate'])->name('complaintdetail.update');

    // corruption
    Route::get('backend/corruption/menu/{menu}', [ServiceBackendController::class, 'SelectCorruption'])->name('list.corruption');
    Route::get('backend/corruptiondetail/menu/{menu}/id/{id}', [ServiceBackendController::class, 'CorruptionDetail'])->name('corruptiondetail');
    Route::post('backend/corruptiondetail/menu/{menu}/id/{id}', [ServiceBackendController::class, 'CorruptionUpdate'])->name('corruptiondetail.update');

    // contact
    Route::get('backend/contact/menu/{menu}', [ServiceBackendController::class, 'SelectContact'])->name('list.contact');
    Route::get('backend/contactdetail/menu/{menu}/id/{id}', [ServiceBackendController::class, 'ContactDetail'])->name('contactdetail');
    Route::post('backend/contactdetail/menu/{menu}/id/{id}', [ServiceBackendController::class, 'ContactUpdate'])->name('contactdetail.update');

    //eservice 
    Route::get('backend/eservice/menu/{menu}', [ServiceBackendController::class, 'listeservice'])->name('list.eservice');
    Route::get('backend/eserviceform/menu/{menu}/id/{id}', [ServiceBackendController::class, 'listeserviceOne'])->name('eserviceform.id');
    Route::post('backend/eservice/menu/{menu}/id/{id}', [ServiceBackendController::class, 'eservicedetailupdate'])->name('eservicedetail.update');
    Route::post('backend/eservice/reply', [ServiceBackendController::class, 'reply'])->name('eservice.reply');

    // webboard
    Route::get('backend/webboard/menu/{menu}', [WebboardBackendController::class, 'listthread'])->name('list.webboard');
    Route::get('backend/threaddetail/menu/{menu}/id/{id}', [WebboardBackendController::class, 'threaddetail'])->name('threaddetail');
    Route::post('backend/threaddetail/menu/{menu}/id/{id}', [WebboardBackendController::class, 'threaddetailupdate'])->name('threaddetail.update');
    Route::get('backend/hidethread/menu/{menu}/id/{id}', [WebboardBackendController::class, 'hidethread'])->name('hidethread');
    Route::get('backend/openthread/menu/{menu}/id/{id}', [WebboardBackendController::class, 'openthread'])->name('openthread');
    Route::get('backend/deletethread/menu/{menu}/id/{id}', [WebboardBackendController::class, 'deletethread'])->name('deletethread');
    Route::get('backend/hidepost/menu/{menu}/id/{id}', [WebboardBackendController::class, 'hidepost'])->name('hidepost');
    Route::get('backend/openpost/menu/{menu}/id/{id}', [WebboardBackendController::class, 'openpost'])->name('openpost');
    Route::get('backend/deletepost/menu/{menu}/id/{id}', [WebboardBackendController::class, 'deletepost'])->name('deletepost');

    //Satisfaction
    Route::get('backend/satisfaction/menu/{menu}', [PublicBackendController::class, 'listsatisfaction'])->name('list.satisfaction');
    Route::get('backend/satisfactionddetail/menu/{menu}/id/{id}', [PublicBackendController::class, 'satisfactiondetail'])->name('satisfactiondetail');

    //popup
    Route::get('backend/popup/menu/{menu}', [PublicBackendController::class, 'popup'])->name('popup.menu');
    Route::post('backend/popup/menu/{menu}', [PublicBackendController::class, 'popupInsert'])->name('popup.insert');

    //calendar
    Route::get('backend/event-calendar/menu/{menu}', [PublicBackendController::class, 'eventcalendar'])->name('eventcalendar.menu');
    Route::get('backend/event-calendar/menu/{menu}/json', [PublicBackendController::class, 'fetchEvents']);
    Route::post('backend/event-calendar/menu/{menu}', [PublicBackendController::class, 'eventcalendarInsert'])->name('eventcalendar.insert');
    Route::put('backend/event-calendar/menu/{menu}/id/{id}', [PublicBackendController::class, 'eventcalendarUpdate'])->name('eventcalendar.update');
    Route::delete('backend/event-calendardelete/menu/{menu}/id/{id}', [PublicBackendController::class, 'eventcalendarDelete'])->name('eventcalendar.delete');

    //vote
    Route::get('backend/vote/menu/{menu}', [PublicBackendController::class, 'selectvote'])->name('selectvote');
    Route::get('backend/addvote/menu/{menu}', [PublicBackendController::class, 'addvote'])->name('addvote');
    Route::post('backend/addvote/menu/{menu}', [PublicBackendController::class, 'insertvote'])->name('vote.insert');
    Route::get('backend/editvote/menu/{menu}/id/{id}', [PublicBackendController::class, 'selectvoterone'])->name('voteone');
    Route::post('backend/editvote/menu/{menu}/id/{id}', [PublicBackendController::class, 'editvote'])->name('editvoteone');
    Route::get('backend/hidevote/menu/{menu}/id/{id}', [PublicBackendController::class, 'hidevote'])->name('hidevote');
    Route::get('backend/openvote/menu/{menu}/id/{id}', [PublicBackendController::class, 'openvote'])->name('openvote');
});


//data
//complaint
Route::get('complaint/menu/{menu}', [ServiceDataController::class, 'indexComplaint'])->name('complaint');
Route::post('complaint/menu/{menu}', [ServiceDataController::class, 'insertComplaint'])->name('complaint.insert');

//corruption
Route::get('corruption/menu/{menu}', [ServiceDataController::class, 'indexCorruption'])->name('corruption');
Route::post('corruption/menu/{menu}', [ServiceDataController::class, 'insertCorruption'])->name('corruption.insert');

//contact
Route::get('contact/menu/{menu}', [ServiceDataController::class, 'indexContact'])->name('contact');
Route::post('contact/menu/{menu}', [ServiceDataController::class, 'insertContact'])->name('contact.insert');

//webboard
Route::get('webboard/menu/{menu}', [WebboardDataController::class, 'webboard'])->name('webboard');
Route::get('newthread/menu/{menu}', [WebboardDataController::class, 'Thread'])->name('thread');
Route::post('newthread/menu/{menu}', [WebboardDataController::class, 'ThreadInsert'])->name('thread.insert');
Route::get('threaddetail/menu/{menu}/id/{id}', [WebboardDataController::class, 'getThreaddetail'])->name('Thread.detail');
Route::post('threaddetail/menu/{menu}/id/{id}', [WebboardDataController::class, 'PostsInsert'])->name('Post.insert');

//eservice 
Route::get('listformeservice/menu/{menu}', [ServiceDataController::class, 'listform'])->name('formeservice');
Route::get('listformeservicepdf/menu/{menu}/id/{id}', [ServiceDataController::class, 'listformpdf'])->name('formeservicepdf');
Route::get('formeservicepdf/export/form/{form}/id/{id}', [ServiceDataController::class, 'GeneralRequestsAdminExportPDF'])->name('GeneralRequestsAdminExportPDF');
Route::get('formeservicepdf/export-test/form/{form}/id/{id}', [ServiceDataController::class, 'GeneralRequestsAdminExportPDFtest'])->name('GeneralRequestsAdminExportPDFtest');
Route::get('formeservicepdf/export-test/form/{form}/id/{id}', [ServiceDataController::class, 'GeneralRequestsAdminExportPDFtest2pdf'])->name('GeneralRequestsAdminExportPDFtest2pdf');
Route::get('formeservice/menu/{menu}/id/{id}', [ServiceDataController::class, 'showform'])->name('showform');
Route::post('formeservice/menu/{menu}/id/{id}', [ServiceDataController::class, 'saveform'])->name('showform.save');

//Satisfaction
Route::get('satisfaction/menu/{menu}', [PublicDataController::class, 'satisfaction'])->name('satisfaction');
Route::post('satisfaction/menu/{menu}', [PublicDataController::class, 'satisfactionInsert'])->name('satisfaction.insert');

//calendar
Route::get('/calendar/menu/{menu}', [PublicDataController::class, 'calendar'])->name('calendar');
Route::get('/calendar/events', [PublicDataController::class, 'getEvents'])->name('calendar.events');

//เมนูหน้าเดียว
Route::get('/articles/menu/{menu}', [ArticlesDataController::class, 'SelectArticlesFront'])->name('articles.data');

//เมนูlist
Route::get('/directory/menu/{menu}', [DirectoryDataController::class, 'SelectDirectoryFront'])->name('directory.data');
Route::get('/directoryDetail/menu/{menu}/id/{id}', [DirectoryDataController::class, 'SelectDirectoryFrontID'])->name('directory.detail');

//หมวดหมู่
Route::get('/categories/menu/{menu}', [DirectoryDataController::class, 'SelectCategoriesFront'])->name('categories.list');
Route::get('/directory/menu/{menu}/cate/{cate}', [DirectoryDataController::class, 'SelectDirectoryCateFront'])->name('categories.data');
Route::get('/directoryDetail/menu/{menu}/id/{id}/cate/{cate}', [DirectoryDataController::class, 'SelectDirectoryCateFrontID'])->name('categories.detail');
