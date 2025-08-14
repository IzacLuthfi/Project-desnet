<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $userId;
    public $message;
    public $channelName;

    public function __construct($userId, $message, $channelName)
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->channelName = $channelName;
    }

    public function broadcastOn(): Channel
    {
        return new Channel($this->channelName);
    }

    public function broadcastAs(): string
    {
        return 'new-notification';
    }
}
