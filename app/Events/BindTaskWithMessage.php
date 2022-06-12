<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BindTaskWithMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;
    public string $message;
    public bool $is_checked;
    public string $date_of_dispatch;
    public $user_sender;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $message, $is_checked, $date_of_dispatch, $user_sender)
    {
        $this->id = $id;
        $this->message = $message;
        $this->is_checked = $is_checked;
        $this->date_of_dispatch = $date_of_dispatch;
        $this->user_sender = $user_sender;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('kanban-sut-new-message');
    }
}
