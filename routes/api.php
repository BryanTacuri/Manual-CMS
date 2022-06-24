<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubsectionController;
use App\Models\Step;
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
    Route::post('category/store', 'store');
    Route::get('category/index', 'index');
    Route::get('category/{id}', 'getById');
    Route::post('category/update/{id}', 'update');
    Route::post('category/delete/{id}', 'delete');
});

Route::controller(TagController::class)->group(function () {
    Route::post('tag/store', 'store');
    Route::get('tag/index', 'index');
    Route::get('tag/{id}', 'getById');
    Route::post('tag/update/{id}', 'update');
    Route::post('tag/delete/{id}', 'delete');
});


Route::controller(ManualController::class)->group(function () {
    Route::post('manual/store', 'store');
    Route::get('manual/index', 'index');
    Route::get('manual/{id}', 'getById');
    Route::post('manual/update/{id}', 'update');
    Route::post('manual/delete/{id}', 'delete');
    Route::get('manual/{id}/section', 'sectionOfManual');
});

Route::controller(SectionController::class)->group(function () {
    Route::post('section/store', 'store');
    Route::get('section/index', 'index');
    Route::get('section/{id}', 'getById');
    Route::post('section/update/{id}', 'update');
    Route::post('section/delete/{id}', 'delete');
    Route::get('section/{id}/subsection', 'subsectionOfSection');
});

Route::controller(SubsectionController::class)->group(function () {
    Route::post('subsection/store', 'store');
    Route::get('subsection/index', 'index');
    Route::get('subsection/{id}', 'getById');
    Route::post('subsection/update/{id}', 'update');
    Route::post('subsection/delete/{id}', 'delete');
});

Route::controller(StepController::class)->group(function () {
    Route::post('step/store', 'store');
    Route::get('step/index', 'index');
    Route::get('step/{id}', 'getById');
    Route::post('step/update/{id}', 'update');
    Route::post('step/delete/{id}', 'delete');
});