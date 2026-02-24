<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
// use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MessageSent extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $user;
    public $message;
    public $conversation;
    public $receiverId;

    public function __construct($user, $message, $conversation, $receiverId)
    {
        $this->user = $user;
        $this->message = $message;
        $this->conversation = $conversation;
        $this->receiverId = $receiverId;
    }

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'user_id' => $this->user->id,
            'message_id' => $this->message->id,
            'conversation_id' => $this->conversation->id,
            'receiver_id' => $this->receiverId,
        ]);
    }
 
 
}