<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Current routes are defined with prefix v1 at the front like /api/v1 and
| then followed with certain routes below
*/
Route::prefix('v1')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Authentication routes with access to /api/v1/auth/
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
        Route::get('refresh', 'AuthController@refresh');
    });

    /*
    |--------------------------------------------------------------------------
    | General access routes with access to /api/v1/general/
    |--------------------------------------------------------------------------
    */
    Route::prefix('general')->group(function () {
        Route::get('course', 'Api\CourseController@generalList');
        Route::get('course/detail/{id}', 'Api\CourseController@generalDetail');
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:api'], function(){
        /*
        |--------------------------------------------------------------------------
        | Authentication routes after login with access to /api/v1/auth/
        |--------------------------------------------------------------------------
        */
        Route::prefix('auth')->group(function () {
            Route::post('logout', 'AuthController@logout');
        });

        /*
        |--------------------------------------------------------------------------
        | Account routes after login with access to /api/v1/account/
        |--------------------------------------------------------------------------
        */
        Route::prefix('account')->group(function () {
            Route::get('me', 'Api\UserController@me');
            Route::get('me/detail', 'Api\UserController@meWithDetail');
            Route::post('update/{id}', 'Api\UserController@meUpdate');
            Route::get('course', 'Api\UserCourseController@read');
            Route::post('course/join', 'Api\UserCourseController@create');
            Route::delete('course/leave/{course}', 'Api\UserCourseController@leave');
        });

        /*
        |--------------------------------------------------------------------------
        | Class room routes after login with access to /api/v1/room/
        |--------------------------------------------------------------------------
        */
        Route::prefix('room')->group(function () {
            Route::get('course/{user_course}/{course}', 'Api\CourseController@room');
            route::get('lesson/{user_course}/{lesson}', 'Api\ModuleLessonController@room');
            Route::get('quiz/{user_course}/{quiz}', 'Api\ModuleQuizController@room');
            Route::get('status/{user_course}/{course}', 'Api\UserCourseController@room');
            Route::post('save/module', 'Api\UserCourseModuleController@saveState');
            Route::post('save/question', 'Api\UserCourseQuizController@saveState');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Routes group who only for administrator that can be access with /api/v1/
|--------------------------------------------------------------------------
*/
Route::middleware(['administrator'])->prefix('v1')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Reporting routes for administrator with access to /api/v1/report/
    |--------------------------------------------------------------------------
    */
    Route::prefix('report')->group(function () {
        Route::get('course/total', 'Api\CourseController@reportTotal');
        Route::get('course/follower', 'Api\UserCourseController@reportFollower');
        Route::get('user', 'Api\UserController@reportCount');
    });

    /*
    |--------------------------------------------------------------------------
    | Master data manipulation routes with access to /api/v1/master/
    |--------------------------------------------------------------------------
    */
    Route::prefix('master')->group(function () {
        Route::get('course', 'Api\CourseController@read');
        Route::post('course', 'Api\CourseController@create');
        Route::post('course/update/{id}', 'Api\CourseController@update');

        Route::get('module', 'Api\ModuleController@read');
        Route::post('module', 'Api\ModuleController@create');
        Route::post('module/update/{id}', 'Api\ModuleController@update');
        
        Route::get('module/lesson', 'Api\ModuleLessonController@read');
        Route::post('module/lesson', 'Api\ModuleLessonController@create');
        Route::post('module/lesson/update/{id}', 'Api\ModuleLessonController@update');

        Route::get('module/quiz', 'Api\ModuleQuizController@read');
        Route::post('module/quiz', 'Api\ModuleQuizController@create');
        Route::post('module/quiz/update/{id}', 'Api\ModuleQuizController@update');

        Route::get('module/quiz/question', 'Api\ModuleQuizQuestionController@read');
        Route::post('module/quiz/question', 'Api\ModuleQuizQuestionController@create');
        Route::post('module/quiz/question/update/{id}', 'Api\ModuleQuizQuestionController@update');

        Route::get('module/quiz/choice', 'Api\ModuleQuizChoiceController@read');
        Route::post('module/quiz/choice', 'Api\ModuleQuizChoiceController@create');
        Route::post('module/quiz/choice/update/{id}', 'Api\ModuleQuizChoiceController@update');

        Route::get('user', 'Api\UserController@read');
        Route::post('user', 'Api\UserController@create');
        Route::post('user/update/{id}', 'Api\UserController@update'); 

        Route::get('user/course', 'Api\UserCourseController@read');
        Route::post('user/course', 'Api\UserCourseController@create');
        Route::post('user/course/update/{id}', 'Api\UserCourseController@update'); 
    });

    /*
    |--------------------------------------------------------------------------
    | Danger zone that used to delete some data with access to /api/v1/advanced/
    |--------------------------------------------------------------------------
    */
    Route::prefix('advanced')->group(function () {
        Route::delete('course/delete/{id}', 'Api\CourseController@delete');
        
        Route::delete('module/delete/{id}', 'Api\ModuleController@delete');
        Route::delete('module/lesson/delete/{id}', 'Api\ModuleLessonController@delete');
        Route::delete('module/quiz/delete/{id}', 'Api\ModuleQuizController@delete');
        Route::delete('module/quiz/question/delete/{id}', 'Api\ModuleQuizQuestionController@delete');
        Route::delete('module/quiz/choice/delete/{id}', 'Api\ModuleQuizChoiceController@delete');
        
        Route::delete('user/delete/{id}', 'Api\UserController@delete');
        Route::delete('user/course/delete/{id}', 'Api\UserCourseController@delete');
    });
});
