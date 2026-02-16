<?php

namespace App\Livewire\Chat;

use Livewire\Component; 
use App\Models\Conversation;
use App\Models\Message;

class ChatBox extends Component
{
    public $selectedConversation; 
    public $conversationId; 

    public function mount($conversationId = null)
    {
        $this->conversationId = $conversationId;
        $this->loadConversation();
    }

    public function loadConversation()
    {
        if ($this->conversationId) {
            $this->selectedConversation = Conversation::with('sender', 'receiver')
                ->find($this->conversationId);
        } else {
            $this->selectedConversation = null;
        }
    }

    // This hook ensures that if the ID changes while the component is alive, it updates
    public function updatedConversationId()
    {
        $this->loadConversation();
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}