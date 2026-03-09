<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        private string $channel,
        private string|array $message,
        private bool $private = false,
    ) {
        Log::info('construct: ' . $this->channel . ' message: ' . $this->message . ' private: ' . $this->private);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::info('broadcast on: ' . $this->channel);
        return $this->private ? new PrivateChannel($this->channel) : new Channel($this->channel);
    }

    public function broadcastWith(): array
    {
        return is_string($this->message) ? ['message' => $this->message] : $this->message;
    }

    public function broadcastAs(): string
    {
        return 'TestBroadcast';
    }

    public function broadcastQueue(): string
    {
        return 'broadcast';
    }
}
