<?php

namespace App\Livewire\Chat;

use Livewire\Component;

use Livewire\Attributes\On; 

class ChatList extends Component
{
    public $selectedConversation = null;
    public $query = null; 

 protected $listeners = ['refresh' => '$refresh'];
     public function render()
    {
        $user = auth()->user();
        return view('livewire/chat.chat-list', [
            'conversations' => $user->conversations()
            ->with('messages', 'sender', 'receiver')
            ->latest('updated_at') 
            ->get()
        ]);
    }
    
  public function selectConversation($conversationId)
{
    $this->dispatch('selectConversation', conversationId: $conversationId);
}

 #[On('refresh-chat-list')]
    public function refreshList()
    { 
        // listener for refresh the chatlist last message show
    }
}