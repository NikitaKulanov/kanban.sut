<?php

use App\Http\Controllers\Api\BindingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KanbanController;
use App\Models\Discipline;
use App\Models\File;
use App\Models\Group;
use App\Models\Link;
use App\Models\Message;
use App\Models\Professor;
use App\Models\Student;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group(['middleware' => 'auth:sanctum'], function (){
    Route::get('/user/logout', [AuthController::class, 'logout']);

    Route::group(['controller' => UserController::class], function (){
        Route::get('/user', 'getUser');
        // Будут ещё маршруты
    });

    Route::apiResources([
        'files' => File::class,
        'links' => Link::class,
        'messages' => Message::class,
        'tasks' => Message::class,
        'disciplines' => Discipline::class,
        'groups' => Group::class,
        'students' => Student::class,
        'professors' => Professor::class
    ]);

    Route::group(
        [
            'controller' => BindingController::class,
            'where' => ['task' => '[1-9]+', 'link' => '[1-9]+', 'file' => '[1-9]+'],
            'prefix' => '/tasks'
        ],
        function (){
            Route::post('/{task}/messages/{message}', 'bindTaskWithMessage');
            Route::post('/{task}/links/{link}', 'bindTaskWithLink');
            Route::post('/{task}/files/{file}', 'bindTaskWithFile');
    });

    Route::group(
        [
            'controller' => KanbanController::class,
            'where' => ['task' => '[1-9]+', 'link' => '[1-9]+', 'file' => '[1-9]+'],
            'prefix' => '/kanban'
        ],
        function (){
            Route::get('/student/tasks', 'getTasksForStudent');
            Route::get('/professor/disciplines/{discipline}/groups/{group}', 'getStudentsForProfessor');
            Route::get('/professor/groups/disciplines', 'getGroupsForProfessor');
        });
});

Route::group(['controller' => AuthController::class, 'prefix' => '/user', 'middleware' => 'guest'], function (){
    Route::middleware('guest')->group(function (){
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });
});
