<?php

namespace App\Http\Controllers;

use App\Contracts\BindingModelContract;
use App\Models\File;
use App\Models\Link;
use App\Models\Message;
use App\Models\Task;
use App\Services\BindingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use function response;

class BindingController extends Controller
{
    /**
     * @var BindingService
     */
    private BindingService $bindingService;

    /**
     * @var array
     */
    private array $idRightsForBinding;

    /**
     * @var Request
     */
    private Request $request;

    public function __construct(array $idRightsForBinding, BindingService $bindingService, Request $request)
    {
        $this->idRightsForBinding = $idRightsForBinding;
        $this->bindingService = $bindingService;
        $this->request = $request;
    }

    /**
     * Bind the task to a message (Create)
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function bindTaskWithMessageCreate(Task $task): JsonResponse
    {
        $requestData = $this->request->all();
        $validator = Validator::make($requestData, [
            'message' => ['required', 'string'],
            'sender_id' => ['required', 'numeric', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Message creation data was not successfully validated!',
                    'failed' => $validator->failed()
                ],
                422
            );
        }

        $messageId = $this->bindingService->createMessage(
            $requestData['message'],
            $requestData['sender_id'],
            $task->id
        );

        return response()->json([
            'id' => $messageId
        ], 200);
    }

    /**
     * Bind the task to a link (Create)
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function bindTaskWithLinkCreate(Task $task): JsonResponse
    {
        $requestData = $this->request->all();
        $validator = Validator::make($requestData, [
            'href' => ['required', 'string'],
            'description' => ['required', 'string'],
            'sender_id' => ['required', 'numeric', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Link creation data was not successfully validated!',
                    'failed' => $validator->failed()
                ],
                422
            );
        }

        $linkId = $this->bindingService->createLink(
            $requestData['href'],
            $requestData['description'],
            $requestData['sender_id']
        );

        $this->bindingService->bindTaskWithLink($linkId, $task->id);

        return response()->json([
            'id' => $linkId
        ], 200);
    }

    /**
     * Bind the task to a file (Create)
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function bindTaskWithFileCreate(Task $task): JsonResponse
    {
        $requestData = $this->request->all();
        $validator = Validator::make($requestData, [
            'file' => ['required', 'file'],
            'sender_id' => ['required', 'numeric', 'exists:users,id'],
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'File creation data was not successfully validated!',
                    'failed' => $validator->failed()
                ],
                422
            );
        }

        $fileId = $this->bindingService->createFile(
            $requestData['file']->getClientOriginalName(),//original_name
            $requestData['file']->getClientOriginalExtension(),//original_extension
            $requestData['file']->hashName(),//name
            $requestData['file']->extension(),//extension
            $requestData['sender_id'],//extension
        );

        $requestData['file']->store('uploads/task-'.$task->id, 'public');// Здесь?

        $this->bindingService->bindTaskWithFile($fileId, $task->id);

        return response()->json([
            'id' => $fileId,
        ], 200);
    }

    /**
     * Bind the task to a message
     *
     * @param Task $task
     * @param Message $message
     * @return JsonResponse
     */
    public function bindTaskWithMessage(Task $task, Message $message): JsonResponse
    {
        if($message->is_deleted == false) {
            $this->rightsCheck($task, $message);
            return response()->json([], 200);
        }
        return response()->json(["message" => ""], 404);
    }

    /**
     * Bind the task to a link
     *
     * @param Task $task
     * @param Link $link
     * @return JsonResponse
     */
    public function bindTaskWithLink(Task $task, Link $link): JsonResponse
    {
        $this->rightsCheck($task, $link);
        return response()->json([], 200);
    }

    /**
     * Bind the task to a file
     *
     * @param Task $task
     * @param File $file
     * @return JsonResponse
     */
    public function bindTaskWithFile(Task $task, File $file): JsonResponse
    {
        $this->rightsCheck($task, $file);
        return response()->json([], 200);
    }

    /**
     * @param Task $task
     * @param BindingModelContract $model
     * @return void
     */
    private function rightsCheck(Task $task, BindingModelContract $model)
    {
        Gate::authorize('rights-check-for-binding', [
            $task,
            $model,
            $this->idRightsForBinding
        ]);
    }
}
