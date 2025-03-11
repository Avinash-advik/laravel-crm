<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomFieldController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::get('/listing/{any?}', [ContactController::class, 'listing'])->name('contact.listing');
Route::post('/store', [ContactController::class, 'store'])->name('contact.store');
Route::get('/delete/{id}', [ContactController::class, 'destroy'])->name('contact.delete');
Route::get('/edit/{id}', [ContactController::class, 'edit'])->name('contact.edit');
Route::post('/update/{id}', [ContactController::class, 'update'])->name('contact.update');
Route::get('/mastercontact', [ContactController::class, 'mastercontact'])->name('contact.mastercontact');
Route::post('/contact/merge', [ContactController::class, 'merge'])->name('contact.merge');

Route::get('/customfield', [CustomFieldController::class, 'index'])->name('customfield.index');
Route::get('/customfield/listing/{any?}', [CustomFieldController::class, 'listing'])->name('customfield.listing');
Route::post('/customfield/store', [CustomFieldController::class, 'store'])->name('customfield.store');
Route::get('/customfield/delete/{id}', [CustomFieldController::class, 'destroy'])->name('customfield.delete');
Route::get('/customfield/edit/{id}', [CustomFieldController::class, 'edit'])->name('customfield.edit');
Route::post('/customfield/update/{id}', [CustomFieldController::class, 'update'])->name('customfield.update');
