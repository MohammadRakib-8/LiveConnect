<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation = null;
    public $query = null; 

    public function render()
    {
        $user = auth()->user();
        return view('livewire/chat.chat-list', [
            'conversations' => $user->conversations()->with('messages', 'sender', 'receiver')->get()
        ]);
    }
    
  public function selectConversation($conversationId)
{
    // Use named parameter 'conversationId' to be explicit
    $this->dispatch('selectConversation', conversationId: $conversationId);
}
}