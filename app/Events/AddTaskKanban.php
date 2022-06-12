<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddTaskKanban implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $card;
    public int $perpetratorId;
    public int $authorId;
    public ?int $disciplineId; // ?

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($card, $perpetratorId, $authorId, $disciplineId = null)
    {
        $this->card = $card;
        $this->perpetratorId = $perpetratorId;
        $this->authorId = $authorId;
        $this->disciplineId = $disciplineId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('kanban-sut-add-task-card');
    }
}
