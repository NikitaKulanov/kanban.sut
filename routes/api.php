<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BindingController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\Resources\DescriptionTaskController;
use App\Http\Controllers\Resources\DisciplineController;
use App\Http\Controllers\Resources\FileController;
use App\Http\Controllers\Resources\GroupController;
use App\Http\Controllers\Resources\LinkController;
use App\Http\Controllers\Resources\MessageController;
use App\Http\Controllers\Resources\ProfessorController;
use App\Http\Controllers\Resources\RightController;
use App\Http\Controllers\Resources\StatusController;
use App\Http\Controllers\Resources\StudentController;
use App\Http\Controllers\Resources\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
date_default_timezone_set ('Europe/Moscow');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user/logout', [AuthController::class, 'logout']);

    Route::group(['controller' => UserController::class], function () {
        Route::get('/user', 'getUser');
        // Будут ещё маршруты
    });

    Route::apiResources([
        'files' => FileController::class,
        'links' => LinkController::class,
        'messages' => MessageController::class,
        'tasks' => TaskController::class,
        'description_task' => DescriptionTaskController::class,
        'disciplines' => DisciplineController::class,
        'groups' => GroupController::class,
        'students' => StudentController::class,
        'professors' => ProfessorController::class,
        'statuses' => StatusController::class,
        'rights' => RightController::class
    ]);

    Route::group(
        [
            'controller' => BindingController::class,
            'where' => ['task' => '[1-9]+', 'link' => '[1-9]+', 'file' => '[1-9]+', 'message' => '[1-9]+'],
            'prefix' => '/tasks'
        ],
        function () {
            Route::post('/{task}/messages', 'bindTaskWithMessageCreate');
            Route::post('/{task}/links', 'bindTaskWithLinkCreate');
            Route::post('/{task}/files', 'bindTaskWithFileCreate');
            Route::post('/{task}/messages/{message}', 'bindTaskWithMessage');
            Route::post('/{task}/links/{link}', 'bindTaskWithLink');
            Route::post('/{task}/files/{file}', 'bindTaskWithFile');
        }
    );

    Route::group(
        [
            'controller' => KanbanController::class,
            'where' => ['discipline' => '[1-9]+', 'group' => '[1-9]+'],
            'prefix' => '/kanban'
        ],
        function () {
            Route::get('/student/tasks', 'getTasksForStudent');
            Route::get('/professor/disciplines/{discipline}/groups/{group}', 'getStudentsForProfessor');
            Route::get('/professor/groups/disciplines', 'getGroupsForProfessor');
        }
    );
});

Route::group(['controller' => AuthController::class, 'prefix' => '/user', 'middleware' => 'guest'], function (){
    Route::middleware('guest')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });
});

//Route::get('/test', function (Request $request){
////    return \Illuminate\Support\Facades\DB::table('messages')->find(1);
////    return Message::find(1)->getSender();
////    $z = \App\Models\Task::find(1)->descriptionTask;
////    $z = \App\Models\Task::find(1)->descriptionTask;
////    $task = Task::find(1);
//////    $task->descriptionTask;
////    $task->load('descriptionTask');
//////    return $task;
//
//
////    if(
////        in_array(
////            \App\Models\UserAccessToTheTask::find(1)->where([
////                ['task_id', '=', '1'],
////                ['user_id', '=', '12'],
////            ])->first()->right_id ?? null,
////            [3, 4, 5]
////        )
////    ){
////        return 3;
////    }
////    return 123;
//
//
//
//    $user = $request->user();
//    $task = Task::find(1);
//    $model = Message::find(2);
//
////    if($task->descriptionTask->is_public == false){
////        return 'Не публичная';
////    }
//
//    if ($user->role === 'administrator') {
//        return 'administrator';
//    } elseif ($user->id === $model->getSenderId()) {
//        $task->load('descriptionTask');
//        if ($user->id === $task->perpetrator_id) {
//            return 'perpetrator';
//        } elseif ($user->id === $task->descriptionTask->author_id) {
//            return 'author';
//        } else {
//            if ($task->descriptionTask->is_public == true) {
//                return 'Публичная';
//            } else {
//                if(in_array(
//                        \App\Models\UserAccessToTheTask::Where([
//                            ['task_id', '=', $task->id],
//                            ['user_id', '=', $user->id],
//                        ])->first()->right_id ?? null,
//                        [3, 4, 5]
//                )){
//                    return 'Он в праве';
//                }
//                return 'Не публичная';
//            }
//        }
//
//    }
//    return 'Хер';
//});

Route::get('/test', function (Request $request){
    return (new DateTime)->format('d.m.Y H:i');
//    foreach (config('sut.rights') as $right){
//        echo $right['id']."<br>";
//        echo $right['title']."<br>";
//        echo '-----------'."<br>";
//    }
//    $qwe = null;
//    dd($qwe?->qw);
//    return dd(config('sut.rights_for_binding'));
});
