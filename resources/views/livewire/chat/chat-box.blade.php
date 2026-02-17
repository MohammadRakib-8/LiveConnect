<div class="flex flex-col h-full relative">
    
    <!-- DEBUGGING BLOCK -->
    <div class="bg-red-500 text-white p-2 text-center font-bold">
        Current Conversation ID: {{ $conversationId ?? 'NULL' }}
    </div>

    @if($conversationId)
        <!-- SHOW CHAT INTERFACE -->

        <!-- Chat Header -->
        <header class="border-b p-3 flex items-center gap-3 bg-white z-10">
            @if($selectedConversation && $selectedConversation->getReceiver())
                <img src="{{ $selectedConversation->getReceiver()->profile_photo_url ?? 'https://i.pravatar.cc/150?img=5' }}"
                     class="w-10 h-10 rounded-full object-cover" />
            @else
                <img src="https://i.pravatar.cc/150?img=5" class="w-10 h-10 rounded-full" />
            @endif

            <div>
                <h6 class="font-semibold">
                    {{ $selectedConversation?->getReceiver()?->name ?? 'User' }}
                </h6>
                <span class="text-xs text-green-500">Online</span>
            </div>
        </header>

        <!-- Messages Area -->
        <!-- Added x-data to listen for scroll event -->
        <main class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" 
              x-data="{ }" 
              x-on:scroll-to-bottom.window="$el.scrollTop = $el.scrollHeight">
            
            @if($loadedMessages && $loadedMessages->count() > 0)
                @foreach($loadedMessages as $message)
                    @if($message->sender_id == auth()->id())
                        <!-- Sender Message (Me) -->
                        <div class="flex justify-end">
                            <div class="flex flex-col items-end max-w-xs lg:max-w-md">
                                <div class="bg-blue-500 text-white p-3 rounded-2xl rounded-tr-none shadow text-sm">
                                    {{ $message->body }}
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1">
                                    {{ $message->created_at->format('H:i') }}
                                </span>
                            </div>
                            <!-- My Avatar -->
                            <img src="{{ auth()->user()->profile_photo_url }}" 
                                 class="w-8 h-8 rounded-full object-cover ml-2 self-end mb-1" />
                        </div>
                    @else
                        <!-- Receiver Message (Them) -->
                        <div class="flex items-start gap-2">
                            <!-- Their Avatar -->
                            @if($message->sender)
                                <img src="{{ $message->sender->profile_photo_url ?? 'https://i.pravatar.cc/150?img=5' }}"
                                     class="w-8 h-8 rounded-full object-cover" />
                            @else
                                <img src="https://i.pravatar.cc/150?img=5" class="w-8 h-8 rounded-full object-cover" />
                            @endif
                            
                            <div class="flex flex-col items-start max-w-xs lg:max-w-md">
                                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow text-sm">
                                    {{ $message->body }}
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1">
                                    {{ $message->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center text-gray-400 text-sm mt-10">No messages yet. Say hello!</div>
            @endif

        </main>

        <!-- Message Input -->
        <footer class="shrink-0 z-10 bg-white inset-x-0">
            <div class="p-2 border-t">
                <form
                    x-data="{body:@entangle('body')}"
                    @submit.prevent="$wire.sendMessage"
                    method="POST" 
                    autocapitalize="off">
                    @csrf
                    <input type="hidden" autocomplete="false" style="display:none">

                    <div class="grid grid-cols-12">
                        <input 
                                x-model="body"
                                type="text"
                                autocomplete="off"
                                autofocus
                                placeholder="write your message here"
                                maxlength="1700"
                                class="col-span-10 bg-gray-100 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg  focus:outline-none"
                         >
                        <button x-bind:disabled="!(body && body.trim())" 
                                class="col-span-2 bg-blue-600 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition" 
                                type='submit'>
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </footer>

    @else
        <!-- EMPTY STATE -->
        <div class="flex flex-col items-center justify-center h-full bg-gray-50 text-center p-6">
            <h3 class="text-xl font-bold text-gray-700">Welcome to Chat</h3>
            <p class="text-gray-500 mt-2">Please select a conversation.</p>
        </div>
    @endif

</div>