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
    public $paginated_var = 10;
    public $oldestMessageId; 

    public function mount($conversationId = null)
    {
        $this->conversationId = $conversationId;
        $this->loadMessages();
    }





    public function loadMessages()
    {
// $count=Message::where('conversation_id', $this->conversationId)->count();

// $this->loadMessages=Message::where('conversation_id', $this->conversationId)
// ->skip($count - $this->paginated_var)
// ->take($this->paginated_var)
// ->get();

//  return $this->loadMessages;

        if ($this->conversationId) {
            $this->selectedConversation = Conversation::with('sender', 'receiver')
                ->find($this->conversationId);

            $messages = Message::where('conversation_id', $this->conversationId)
                ->with('sender') 
                ->latest('id')
                ->take($this->paginated_var) 
                ->get();

            $this->loadedMessages = $messages->reverse();

            if ($messages->isNotEmpty()) {
                $this->oldestMessageId = $messages->first()->id;
            }

        } else {
            $this->selectedConversation = null;
            $this->loadedMessages = collect();
        }
    }


// public function loadMore():void{

// $this->paginated_var += 10;
// $this->loadMessages();

// }


    public function loadMessagesMore()
    {
        if (!$this->oldestMessageId) {
            return;
        }

        $olderMessages = Message::where('conversation_id', $this->conversationId)
            ->where('id', '<', $this->oldestMessageId)
            ->with('sender')
            ->latest('id')
            ->take($this->paginated_var)
            ->get()
            ->reverse();

        if ($olderMessages->isNotEmpty()) {
            $this->loadedMessages = $olderMessages->concat($this->loadedMessages);
            $this->oldestMessageId = $olderMessages->first()->id;
        }



    }

    public function updatedConversationId()
    {
        $this->paginated_var = 10; 
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
            Auth()->User(),
            $createdMessage,
            $this->selectedConversation,
            $this->selectedConversation->getReceiver()->id
        ));
    }

    //     $receiver = $this->selectedConversation->getReceiver();

    //     if ($receiver) {
    //         try {
    //             Log::info('Sending Pusher Event to Channel: private-chat.' . $receiver->id);

    //             $receiver->notify(new MessageSent(
    //                 auth()->user(),
    //                 $createdMessage,
    //                 $this->selectedConversation,
    //                 $receiver->id
    //             ));
                
    //             Log::info('ChatBox: Notification sent successfully.');
    //         } catch (\Exception $e) {
    //             Log::error('ChatBox: Notification failed. Error: ' . $e->getMessage());
    //         }
    //     } else {
    //         Log::warning('ChatBox: Receiver not found.');
    //     }
    // }

    public function getListeners()
    {
        $auth_id = auth()->id();
        return [
             "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated"=> 'broadcastNotifications'
        ];
    }

    public function broadcastNotifications($event)
    {
        // 1. Safety check: If no conversation is selected, stop here.
    if (!$this->selectedConversation) {
        return;
    }
    //    dd($event);
     if ($event['type'] == MessageSent::class) {

            if ($event['conversation_id'] == $this->selectedConversation->id) {

                $this->dispatch('scroll-bottom');

                $newMessage = Message::find($event['message_id']);


                $this->loadedMessages->push($newMessage);

                   $newMessage->read_at = now();
                   $newMessage->save();

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