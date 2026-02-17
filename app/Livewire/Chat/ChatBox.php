<?php

namespace App\Livewire\Chat;

use Livewire\Component; 
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Validator; // Import for manual validation

class ChatBox extends Component
{
    public $selectedConversation; 
    public $conversationId; 
    public $body;
    public $loadedMessages; // New variable to hold messages

    public function mount($conversationId = null)
    {
        $this->conversationId = $conversationId;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if ($this->conversationId) {
            // Load Conversation AND Messages together
            $this->selectedConversation = Conversation::with('sender', 'receiver')
                ->find($this->conversationId);

            // Load Messages separately to avoid the $messages conflict
            $this->loadedMessages = Message::where('conversation_id', $this->conversationId)
                ->with('sender') // Load sender for avatars
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $this->selectedConversation = null;
            $this->loadedMessages = collect();
        }
    }

    public function updatedConversationId()
    {
        $this->loadMessages();
    }

    public function sendMessage()
    {
        // 1. Manual Validation (Fixes the crash)
        Validator::make([
            'body' => $this->body
        ], [
            'body' => 'required|string|max:1700'
        ])->validate();

        // 2. Safety Check
        if (!$this->selectedConversation) {
            return;
        }

        // 3. Create Message
        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body
        ]);

        // 4. Clear Input
        $this->reset('body');

        // 5. Reload Messages to show the new one
        $this->loadMessages();
        
        // 6. Dispatch event to scroll to bottom (Optional)
        $this->dispatch('scroll-to-bottom');
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}