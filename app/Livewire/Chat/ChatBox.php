<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\MessageSent;
use App\Notifications\MessageRead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; 
use Livewire\Component; 

class ChatBox extends Component
{
    public $selectedConversation; 
    public $conversationId; 
    public $body;
    public $loadedMessages; 
    public $paginate_var = 10;
    public $oldestMessageId; 

    public function mount($conversationId = null)
    {
        $this->conversationId = $conversationId;
        $this->loadMessages();
    }

    protected $listeners = [
        'loadMore'
    ];

    public function loadMore(): void
    {
        // dd('detected');
               $this->paginate_var += 10;

        $this->loadMessagesMore();
        $this->dispatch('update-chat-height');
    }

    public function loadMessages()
    {
        if ($this->conversationId) {
            $this->selectedConversation = Conversation::with('sender', 'receiver')
                ->find($this->conversationId);

            $messages = Message::where('conversation_id', $this->conversationId)
                ->with('sender') 
                ->latest('id')
                ->take($this->paginate_var) 
                ->get();

            $this->loadedMessages = $messages->reverse();

            if ($messages->isNotEmpty()) {
                // We capture the oldest ID so we know where to start fetching older messages from
                $this->oldestMessageId = $messages->first()->id;
            }

        } else {
            $this->selectedConversation = null;
            $this->loadedMessages = collect();
        }
    }


//     public function loadMessages()
//     {
//         if ($this->conversationId) {
            
//             $this->selectedConversation = Conversation::with('sender', 'receiver')
//                 ->find($this->conversationId);

//             $userId = auth()->id();

//             $count = Message::where('conversation_id', $this->selectedConversation->id)
//                 ->where(function ($query) use ($userId) {
//                     $query->where('sender_id', $userId)
//                         ->whereNull('sender_deleted_at');
//                 })->orWhere(function ($query) use ($userId) {
//                     $query->where('receiver_id', $userId)
//                         ->whereNull('receiver_deleted_at');
//                 })
//                 ->count();
// // 10
//             $messages = Message::where('conversation_id', $this->selectedConversation->id)
//                 ->where(function ($query) use ($userId) {
//                     $query->where('sender_id', $userId)
//                         ->whereNull('sender_deleted_at');
//                 })->orWhere(function ($query) use ($userId) {
//                     $query->where('receiver_id', $userId)
//                         ->whereNull('receiver_deleted_at');
//                 })
//                 ->skip($count - $this->paginate_var)
//                 ->take($this->paginate_var)
//                 ->get();

//             $this->loadedMessages = $messages->reverse()->values();

//             if ($messages->isNotEmpty()) {
//                 $this->oldestMessageId = $messages->first()->id;
//             }

//         } else {
//             $this->selectedConversation = null;
//             $this->loadedMessages = collect();
//         }
//     }


    public function loadMessagesMore()
    {
        if (!$this->oldestMessageId) {
            return;
        }
        $olderMessages = Message::where('conversation_id', $this->conversationId)
            ->where('id', '<', $this->oldestMessageId)
            ->with('sender')
            ->latest('id')
            ->take($this->paginate_var)
            ->get()
            ->reverse();

        if ($olderMessages->isNotEmpty()) {
            $this->loadedMessages = $olderMessages->concat($this->loadedMessages);
            
            $this->oldestMessageId = $olderMessages->first()->id;
        }
    }

    public function updatedConversationId()
    {
        $this->paginate_var = 10; 
        $this->oldestMessageId = null;
        $this->loadMessages();
    }

    public function sendMessage()
    {
        Log::info('ChatBox: Message created, preparing to notify.');

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

        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        $this->loadedMessages->push($createdMessage);
        
        $this->dispatch('scroll-to-bottom');
        $this->dispatch('refresh-chat-list');

        $this->selectedConversation->getReceiver()->notify(new MessageSent(
            auth()->user(),
            $createdMessage,
            $this->selectedConversation,
            $this->selectedConversation->getReceiver()->id
        ));
    }

    public function getListeners()
    {
        $auth_id = auth()->id();
        return [
            'loadMore',
             "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated"=> 'broadcastNotifications'
        ];
    }

    public function broadcastNotifications($event)
    {
        if (!$this->selectedConversation) {
            return;
        }

        if ($event['type'] == MessageSent::class) {
            if ($event['conversation_id'] == $this->selectedConversation->id) {
                
                $this->dispatch('scroll-to-bottom'); 

                $newMessage = Message::find($event['message_id']);

                if ($newMessage && !$this->loadedMessages->contains('id', $newMessage->id)) {
                    $this->loadedMessages->push($newMessage);
                }

                if ($newMessage) {
                    $newMessage->read_at = now();
                    $newMessage->save();
                }

                $this->selectedConversation->getReceiver()
                ->notify(new MessageRead($this->selectedConversation->getReceiver()->id));
            }   
        }
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}