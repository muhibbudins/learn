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
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('me', 'Api\UserController@me');
        Route::post('logout', 'AuthController@logout');

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
});

Route::middleware(['is_admin'])->prefix('v1')->group(function () {
    Route::post('course', 'Api\CourseController@create');
    Route::post('course/{id}', 'Api\CourseController@update');
    Route::delete('course/{id}', 'Api\CourseController@delete');

    Route::post('course/history', 'Api\CourseHistoryController@create');
    Route::post('course/history/{id}', 'Api\CourseHistoryController@update');
    Route::delete('course/history/{id}', 'Api\CourseHistoryController@delete');
    
    Route::post('course/module', 'Api\CourseModuleController@create');
    Route::post('course/module/{id}', 'Api\CourseModuleController@update');
    Route::delete('course/module/{id}', 'Api\CourseModuleController@delete');

    Route::post('module', 'Api\ModuleController@create');
    Route::post('module/{id}', 'Api\ModuleController@update');
    Route::delete('module/{id}', 'Api\ModuleController@delete');
    
    Route::post('module/lesson', 'Api\ModuleLessonController@create');
    Route::post('module/lesson/{id}', 'Api\ModuleLessonController@update');
    Route::delete('module/lesson/{id}', 'Api\ModuleLessonController@delete');
    
    Route::post('module/quiz', 'Api\ModuleQuizController@create');
    Route::post('module/quiz/{id}', 'Api\ModuleQuizController@update');
    Route::delete('module/quiz/{id}', 'Api\ModuleQuizController@delete');
    
    Route::post('module/quiz/choice', 'Api\ModuleQuizChoiceController@create');
    Route::post('module/quiz/choice/{id}', 'Api\ModuleQuizChoiceController@update');
    Route::delete('module/quiz/choice/{id}', 'Api\ModuleQuizChoiceController@delete');

    Route::post('user', 'Api\UserController@create');
    Route::post('user/{id}', 'Api\UserController@update');
    Route::delete('user/{id}', 'Api\UserController@delete');

    Route::post('user/course', 'Api\UserCourseController@create');
    Route::post('user/course/{id}', 'Api\UserCourseController@update');
    Route::delete('user/course/{id}', 'Api\UserCourseController@delete');
});
