<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubsectionController;
use App\Http\Controllers\FileController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('me', 'me');
});

Route::controller(CategoryController::class)->group(function () {
    Route::post('category', 'store');
    Route::get('category', 'index');
    Route::get('category/{id}', 'getById');
    Route::put('category/{id}', 'update');
    Route::delete('category/{id}', 'delete');
});

Route::controller(TagController::class)->group(function () {
    Route::post('tag', 'store');
    Route::get('tag', 'index');
    Route::get('tag/{id}', 'getById');
    Route::put('tag/{id}', 'update');
    Route::delete('tag/{id}', 'delete');
});


Route::controller(ManualController::class)->group(function () {
    Route::post('manual', 'store');
    Route::get('manual', 'index');
    Route::get('manual/{id}', 'getById');
    Route::put('manual/{id}', 'update');
    Route::delete('manual/{id}', 'delete');
    Route::get('manual/{id}/section', 'sectionOfManual');
});

Route::controller(SectionController::class)->group(function () {
    Route::post('section/store', 'store');
    Route::get('section/index', 'index');
    Route::get('section/{id}', 'getById');
    Route::put('section/{id}', 'update');
    Route::delete('section/{id}', 'delete');
    Route::get('section/{id}/subsection', 'subsectionOfSection');
});

Route::controller(SubsectionController::class)->group(function () {
    Route::post('subsection', 'store');
    Route::get('subsection', 'index');
    Route::get('subsection/{id}', 'getById');
    Route::put('subsection/{id}', 'update');
    Route::delete('subsection/{id}', 'delete');
});

Route::controller(StepController::class)->group(function () {
    Route::post('step', 'store');
    Route::get('step', 'index');
    Route::get('step/{id}', 'getById');
    Route::put('step/{id}', 'update');
    Route::delete('step/{id}', 'delete');
});

Route::controller(FileController::class)->group(function () {
    Route::post('file', 'store');
    Route::get('file', 'index');
    Route::get('file/{id}', 'getById');
    Route::put('file/{id}', 'update');
    Route::delete('file/{id}', 'delete');
});