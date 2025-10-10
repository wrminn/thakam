<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/backend', function () {
//     return view('admin.layout');
// });

// Route::get('backend',[AdminController::class,'about'])->name('backend');

Route::get('backend/list/menu/{menu}',[AdminController::class,'list'])->name('list.menu');
Route::get('backend/add/menu/{menu}',[AdminController::class,'add'])->name('menu');
Route::get('backend/delete/menu/{menu}/id/{id}',[AdminController::class,'delete'])->name('delete.menu');
Route::get('backend/edit/menu/{menu}/id/{id}',[AdminController::class,'edit'])->name('edit.menu.id');

// บันทึกข้อมูล
Route::post('backend/add/menu/{menu}',[AdminController::class,'insert'])->name('texteditor.insert');
Route::post('backend/edit/menu/{menu}/id/{id}',[AdminController::class,'update'])->name('texteditor.update');
