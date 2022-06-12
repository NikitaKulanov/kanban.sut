<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\DescriptionTask;
use App\Models\Task;
use App\Services\KanbanService;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var KanbanService
     */
    private KanbanService $kanbanService;

    /**
     * @param Request $request
     * @param KanbanService $kanbanService
     */
    public function __construct(Request $request, KanbanService $kanbanService)
    {
        $this->request = $request;
        $this->kanbanService = $kanbanService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $requestData = $this->request->all();
        $validator = Validator::make($requestData, [
            'deadline' => ['required', 'string'],
            'title' => ['required', 'string'],
            'theme' => ['required', 'string'],
            'description' => ['required', 'string'],
            'author_id' => ['required', 'numeric', 'exists:users,id'],
            'discipline_id' => ['required', 'numeric'],
            'group_id' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return $this->incorrectData(
                'Task creation data was not successfully validated!',
                $validator->failed()
            );
        }
        return response()->json(
            $this->kanbanService->addTasksForStudents(
                $requestData['author_id'],
                $requestData['discipline_id'],
                $requestData['title'],
                $requestData['theme'],
                $requestData['description'],
                $requestData['deadline'],
                $requestData['group_id']
            ), 200); // Это именно для студентов
//        return response()->json(['tasks_id' => [1,2,3]], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->makeHidden([
            'status_id',
            'perpetrator_id',
            'description_task_id',
            'is_deleted',
            'created_at',
            'updated_at',
        ]);
        $task->load('status');
        $task->load('userPerpetrator');
        $task->descriptionTask->makeHidden(
            [
                'discipline_id',
                'author_id',
                'is_public',
                'created_at',
                'updated_at',
            ]
        )->load('discipline', 'userAuthor');
        $task->descriptionTask->deadline = (new DateTime($task->descriptionTask->deadline))->format('d.m.Y H:i');
        $task->descriptionTask->appointment = (new DateTime($task->descriptionTask->appointment))->format('d.m.Y H:i');

        $task->messages->transform(function ($message) {
            $message->date_of_dispatch = (new DateTime($message->created_at))->format('d.m.Y H:i');
            return $message;
        })->makeHidden(
            [
                'task_id',
                'sender_id',
                'created_at',
                'updated_at',
                'is_deleted',
            ]
        )->load('userSender');

        $task->links->makeHidden(
            [
                'pivot',
                'created_at',
                'updated_at',
                'sender_id',
            ]
        )->load('userSender');

        $task->files->transform(function ($item) {
            $item->href = asset('/storage/uploads/task-' . $item->pivot->task_id . '/' . $item->name);
            return $item;
        })->makeHidden(
            [
                'extension',
                'pivot',
                'created_at',
                'updated_at',
                'sender_id',
                'name',
            ]
        )->load('userSender');
        return response()->json($task);
//        return response()->json([
//            "id" => 3,
//            "title"=> "Практическая работа №1",
//            "theme"=> "Введение",
//            "description" => "Это описание",
//            "perpetrator_id" => 1,
//            "author_id" => 6,
//            "discipline_id" => 4,
//            "appointment" => "2022-03-21 01:42:26",
//            "deadline" => "2022-03-21 04:42:00",
//            "estimate" => "Не оценено",
//            "status" => [
//                "id" => 1,
//                "title" => "К ВЫПОЛНЕНИЮ"
//            ],
//            "discipline" => [
//                "id" => 4,
//                "abbreviation" => "МКС",
//                "title" => "Масштабирование компьютерных сетей"
//            ],
//            "user_perpetrator" => [
//                "id" => 1,
//                "surname" => "Куланов",
//                "name" => "Никита",
//                "patronymic" => "Вячеславович",
//                "role" => "student",
//                "avatar" => "img-62346.jfif"
//            ],
//            "user_author" => [
//                "id" => 6,
//                "surname" => "Гвоздков",
//                "name" => "Игорь",
//                "patronymic" => "Вячеславович",
//                "role" => "professor",
//                "avatar" => "gvozdkov.jpg"
//            ],
//            "messages" => [
//                [
//                    "id" => 1,
//                    "message" => "Добрый вечер!",
//                    "is_checked" => 1,
//                    "date_of_dispatch" => "20.03.2022 23:53",
//                    "user_sender" => [
//                        "id" => 14,
//                        "surname" => "Гвоздков",
//                        "name" => "Игорь",
//                        "patronymic" => "Вячеславович",
//                        "role" => "professor",
//                        "avatar" => "gvozdkov.jpg"
//                    ]
//                ],
//                [
//                    "id" => 4,
//                    "message" => "Добрый вечер!",
//                    "is_checked" => 0,
//                    "date_of_dispatch" => "09.04.2022 08:18",
//                    "user_sender" => [
//                        "id" => 11,
//                        "surname" => "Куланов",
//                        "name" => "Никита",
//                        "patronymic" => "Вячеславович",
//                        "role" => "student",
//                        "avatar" => "img-62346.jfif"
//                    ]
//                ]
//            ],
//            "links" => [
//                [
//                    "id" => 13,
//                    "href" => "https://developer.mozilla.org/ru/docs/Web/HTTP/Status",
//                    "description" => "Новая ссылка"
//                ],
//                [
//                    "id" =>  20,
//                    "href" => "https://masteringjs.io/tutorials/axios/post-headers",
//                    "description" => "Вторая ссылка"
//                ]
//            ],
//            "files" => [
//                [
//                    "id" => 9,
//                    "original_name" => "iwzJBWb3fGM.jpg",
//                    "original_extension" => "jpg",
//                    "name" =>  "w55VQhIjODlz4v4Vu4ECDnBhlFZo6gwtU37xm4ce.jpg",
//                    "sender_id" => 1,
//                    "href" => "http://f7ba-146-120-76-123.ngrok.io/storage/uploads/task-73/w55VQhIjODlz4v4Vu4ECDnBhlFZo6gwtU37xm4ce.jpg",
//                    "user_sender" => [
//                        "id" => 1,
//                        "surname" => "Куланов",
//                        "name" => "Никита",
//                        "patronymic" => "Вячеславович",
//                        "role" => "student",
//                        "avatar" => "img-62346.jfif"
//                    ]
//                ],
//                [
//                    "id" => 10,
//                    "original_name" => "123.png",
//                    "original_extension" => "png",
//                    "name" => "BGTMWEyIIU1YqFrE0Hym3Z1IvgSWfMpSUZHpwhsE.png",
//                    "sender_id" => 1,
//                    "href" => "http://f7ba-146-120-76-123.ngrok.io/storage/uploads/task-73/BGTMWEyIIU1YqFrE0Hym3Z1IvgSWfMpSUZHpwhsE.png",
//                    "user_sender" => [
//                        "id" => 1,
//                        "surname" => "Куланов",
//                        "name" => "Никита",
//                        "patronymic" => "Вячеславович",
//                        "role" => "student",
//                        "avatar" => "img-62346.jfif"
//                    ]
//                ]
//            ],
//        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update($id)
    {
        $task = Task::findOrFail($id);
        if(empty($requestData = $this->request->all())){
            return $this->incorrectData('No data in the request!');
        }
        $validator = Validator::make($requestData, [
            'status_id' => ['numeric', 'exists:statuses,id'],
//            'estimate' => ['string'],
        ]);
        if ($validator->fails()) {
            return $this->incorrectData('Data is incorrect!', $validator->failed());
        }

        foreach ($requestData as $key => $item){
            switch ($key) {
                case 'status_id':
                    $this->kanbanService->editTaskStatus($task, $item);
                    break;
                case 'estimate':
                    $this->kanbanService->editTaskEstimate($task, $item);
                    break;
            }
        }

        return response()->json([
            'id' => $id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return response()->json([], 200);
    }

    // Заглушка
    private function incorrectData($message, $failedValidation = null): JsonResponse
    {
        $response = [];
        $response['message'] = $message;
        $response['failed'] = $failedValidation ?? null;
        return response()->json(
            $response,
            422
        );
    }
}
