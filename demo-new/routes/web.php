<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware('guest')->group(function () {
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// });
Route::middleware(['auth'])->group(function () {

    Route::get('backend', [AdminController::class, 'backend'])->name('backend');
    Route::get('backend/add/menu/{menu}', [AdminController::class, 'add'])->name('menu');


    Route::get('backend/list/menu/{menu}', [AdminController::class, 'list'])->name('list');
    Route::post('backend/add/menu/{menu}', [AdminController::class, 'insert'])->name('texteditor.insert');
    Route::get('backend/edit/menu/{menu}/id/{id}', [AdminController::class, 'edit'])->name('listedit');
    Route::post('backend/update/menu/{menu}/id/{id}', [AdminController::class, 'update'])->name('texteditor.update');
    Route::get('backend/delete/menu/{menu}/id/{id}', [AdminController::class, 'delete'])->name('listdelete');
    Route::get('backend/deletelistfile/menu/{menu}/id/{id}/idfile/{idfile}', [AdminController::class, 'deletelistfile'])->name('delete.listfile');


    //texteditor
    Route::get('backend/listtexteditor/menu/{menu}', [AdminController::class, 'listtexteditor'])->name('listtexteditor.menu');
    Route::post('backend/listtexteditor/menu/{menu}', [AdminController::class, 'inserttexteditor'])->name('listtexteditor.insert');
    Route::get('backend/deletefile/menu/{menu}/id/{id}', [AdminController::class, 'deletetexteditorfile'])->name('delete.filetexteditor');
});


