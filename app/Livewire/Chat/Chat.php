<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;

class Chat extends Component
{
    public $query;
    public $selectedConversation;

    protected $listeners = ['selectConversation'];

    public function selectConversation($conversationId)
    {
        $this->selectedConversation = Conversation::with('sender', 'receiver')
            ->findOrFail($conversationId);

        Message::where('conversation_id', $this->selectedConversation->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function mount($query = null)
    {
        if ($query) {
            $this->selectedConversation = Conversation::with('sender', 'receiver')
                ->findOrFail($query);

            Message::where('conversation_id', $this->selectedConversation->id)
                ->where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    public function render()
    {
        return view('livewire.chat.chat');
    }
}