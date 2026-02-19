<?php

namespace App\Livewire\Chat;

use Livewire\Component; 
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Validator; 

class ChatBox extends Component
{
    public $selectedConversation; 
    public $conversationId; 
    public $body;
    public $loadedMessages; 
    public $paginated_var=10;

    public function mount($conversationId = null)
    {
        $this->conversationId = $conversationId;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if ($this->conversationId) {
            $this->selectedConversation = Conversation::with('sender', 'receiver')
                ->find($this->conversationId);

            $this->loadedMessages = Message::where('conversation_id', $this->conversationId)
                ->with('sender') 
                ->orderBy('created_at', 'asc')
                ->get();
//count
$countMessages = Message::where('conversation_id', $this->conversationId)->count();

                //skip and query 
                $this->loadMessages=Message::where('conversation_id', $this->conversationId)
                ->skip($countMessages - $this->paginated_var)
                ->take($this->paginated_var)
                ->get();

                return $this->loadedMessages;


        } else {
            $this->selectedConversation = null;
            $this->loadedMessages = collect();
        }
    }

    public function loadMessagesMore()
    {
        $this->paginated_var += 10;
        $this->loadMessages();
    }

    public function updatedConversationId()
    {
        $this->loadMessages();
    }

    public function sendMessage()
    {
        Validator::make([
            'body' => $this->body
        ], [
            'body' => 'required|string|max:1700'
        ])->validate();

        if (!$this->selectedConversation) {
            return;
        }

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body
        ]);

    
        $this->reset('body');

         // Updatedd Conversation  at the top when a new message is sent to ensure the latest conversation order in the list
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        $this->loadMessages();
        
        $this->dispatch('scroll-to-bottom');

           $this->dispatch('refresh-chat-list');

    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}