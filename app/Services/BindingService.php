<?php

namespace App\Services;

use App\Events\BindTaskWithMessage;
use App\Events\EditTaskStatus;
use App\Models\File;
use App\Models\FileTask;
use App\Models\Link;
use App\Models\LinkTask;
use App\Models\Message;
use DateTime;

class BindingService
{
    public function createLink(string $href, string $description, int $senderId): int
    {
        $link = new Link;
        $link->href = $href;
        $link->description = $description;
        $link->sender_id = $senderId;
        $link->save();
        return $link->id;
    }

    public function createFile(
        string $originalName,
        string $originalExtension,
        string $name,
        string $extension,
        int $senderId
    ): int
    {
        $file = new File;
        $file->original_name = $originalName;
        $file->original_extension = $originalExtension;
        $file->name = $name;
        $file->extension = $extension;
        $file->sender_id = $senderId;
        $file->save();
        return $file->id;
    }

    public function createMessage(string $messageText, int $senderId, int $taskId): int
    {
        $message = new Message;
        $message->message = $messageText;
        $message->task_id = $taskId;
        $message->sender_id = $senderId;
        $message->save();

        BindTaskWithMessage::dispatch(
            $message->id,
            $message->message,
            true,
            (new DateTime($message->created_at))->format('d.m.Y H:i'),
            $message->userSender
        ); // Переделать

        return $message->id;
    }

    public function bindTaskWithLink(int $linkId, int $taskId): int
    {
        return LinkTask::firstOrCreate([
            'task_id' => $taskId,
            'link_id' => $linkId,
        ])->id;
    }

    public function bindTaskWithFile(int $fileId, int $taskId): int
    {
        return FileTask::firstOrCreate([
            'task_id' => $taskId,
            'file_id' => $fileId,
        ])->id;
    }
}
