<?php

use Illuminate\Http\Request;

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
Route::prefix('v1')->group(function () {
    Route::post('register', 'Api\UserController@register');
    Route::post('login', 'Api\UserController@login');
});

Route::middleware(['auth.roles:admin,lecturer,student'])->prefix('v1')->group(function () {
    Route::get('course', 'Api\CourseController@all');
    Route::get('course/history', 'Api\CourseHistoryController@all');
    Route::get('course/module', 'Api\CourseModuleController@all');

    Route::get('module', 'Api\ModuleController@all');
    Route::get('module/lesson', 'Api\ModuleLessonController@all');
    Route::get('module/quiz', 'Api\ModuleQuizController@all');
    Route::get('module/quiz/choice', 'Api\ModuleQuizChoiceController@all');

    Route::get('user', 'Api\UserController@all');
    Route::get('user/course', 'Api\UserCourseController@all');
});

Route::middleware(['auth.roles:admin,lecturer'])->prefix('v1')->group(function () {
    Route::post('course', 'Api\CourseController@create');
    Route::post('course/history', 'Api\CourseHistoryController@create');
    Route::post('course/module', 'Api\CourseModuleController@create');

    Route::post('module', 'Api\ModuleController@create');
    Route::post('module/lesson', 'Api\ModuleLessonController@create');
    Route::post('module/quiz', 'Api\ModuleQuizController@create');
    Route::post('module/quiz/choice', 'Api\ModuleQuizChoiceController@create');

    Route::post('course', 'Api\CourseController@update');
    Route::post('course/history', 'Api\CourseHistoryController@update');
    Route::post('course/module', 'Api\CourseModuleController@update');

    Route::post('module', 'Api\ModuleController@update');
    Route::post('module/lesson', 'Api\ModuleLessonController@update');
    Route::post('module/quiz', 'Api\ModuleQuizController@update');
    Route::post('module/quiz/choice', 'Api\ModuleQuizChoiceController@update');

    Route::delete('course', 'Api\CourseController@delete');
    Route::delete('course/history', 'Api\CourseHistoryController@delete');
    Route::delete('course/module', 'Api\CourseModuleController@delete');

    Route::delete('module', 'Api\ModuleController@delete');
    Route::delete('module/lesson', 'Api\ModuleLessonController@delete');
    Route::delete('module/quiz', 'Api\ModuleQuizController@delete');
    Route::delete('module/quiz/choice', 'Api\ModuleQuizChoiceController@delete');
});

Route::middleware(['auth.roles:admin'])->prefix('v1')->group(function () {
    Route::post('user', 'Api\UserController@create');
    Route::post('user/course', 'Api\UserCourseController@create');

    Route::post('user', 'Api\UserController@update');
    Route::post('user/course', 'Api\UserCourseController@update');

    Route::delete('user', 'Api\UserController@delete');
    Route::delete('user/course', 'Api\UserCourseController@delete');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
