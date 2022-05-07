<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Link;
use App\Models\Message;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class BindingController extends Controller
{
    /**
     * Bind the task to a message
     *
     * @param Task $task
     * @param Message $message
     * @return JsonResponse
     */
    public function bindTaskWithMessage(Task $task, Message $message): JsonResponse
    {
        return response()->json([], 200);
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
        return response()->json([], 200);
    }
}
