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
        Route::post('logout', 'AuthController@logout');

        Route::get('me', 'Api\UserController@me');

        Route::get('course', 'Api\CourseController@read');

        Route::get('module/quiz', 'Api\ModuleQuizController@read');

        Route::get('user', 'Api\UserController@read');
        Route::get('user/course', 'Api\UserCourseController@read');
        Route::get('user/course/module', 'Api\UserCourseModuleController@read');
        Route::get('user/course/quiz', 'Api\UserCourseQuizController@read');

        Route::post('user/course', 'Api\UserCourseController@create');
        Route::post('user/course/update/{id}', 'Api\UserCourseController@update');

        Route::post('user/course/module', 'Api\UserCourseModuleController@create');
        Route::post('user/course/module/update/{id}', 'Api\UserCourseModuleController@update');

        Route::post('user/course/quiz', 'Api\UserCourseQuizController@create');
        Route::post('user/course/quiz/update/{id}', 'Api\UserCourseQuizController@update');
    });
});

Route::middleware(['administrator'])->prefix('v1')->group(function () {
    Route::post('course', 'Api\CourseController@create');
    Route::post('course/update/{id}', 'Api\CourseController@update');
    Route::delete('course/delete/{id}', 'Api\CourseController@delete');
    
    Route::post('course/module', 'Api\CourseModuleController@create');
    Route::post('course/module/update/{id}', 'Api\CourseModuleController@update');
    Route::delete('course/module/delete/{id}', 'Api\CourseModuleController@delete');

    Route::get('module', 'Api\ModuleController@read');
    Route::post('module', 'Api\ModuleController@create');
    Route::post('module/update/{id}', 'Api\ModuleController@update');
    Route::delete('module/delete/{id}', 'Api\ModuleController@delete');
    
    Route::get('module/lesson', 'Api\ModuleLessonController@read');
    Route::post('module/lesson', 'Api\ModuleLessonController@create');
    Route::post('module/lesson/update/{id}', 'Api\ModuleLessonController@update');
    Route::delete('module/lesson/delete/{id}', 'Api\ModuleLessonController@delete');
    
    Route::post('module/quiz', 'Api\ModuleQuizController@create');
    Route::post('module/quiz/update/{id}', 'Api\ModuleQuizController@update');
    Route::delete('module/quiz/delete/{id}', 'Api\ModuleQuizController@delete');
    
    Route::get('module/quiz/choice', 'Api\ModuleQuizChoiceController@read');
    Route::post('module/quiz/choice', 'Api\ModuleQuizChoiceController@create');
    Route::post('module/quiz/choice/update/{id}', 'Api\ModuleQuizChoiceController@update');
    Route::delete('module/quiz/choice/delete/{id}', 'Api\ModuleQuizChoiceController@delete');

    Route::post('user', 'Api\UserController@create');
    Route::post('user/update/{id}', 'Api\UserController@update');
    Route::delete('user/delete/{id}', 'Api\UserController@delete');

    Route::delete('user/course/delete/{id}', 'Api\UserCourseController@delete');

    Route::delete('user/course/module/delete/{id}', 'Api\UserCourseModuleController@delete');

    Route::delete('user/course/quiz/delete/{id}', 'Api\UserCourseQuizController@delete');
});
