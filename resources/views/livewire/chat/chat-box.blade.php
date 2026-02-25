<div   
x-data="
{{-- {height:0,
    conversationElement:document.getElementById('conversation')} --}}
    ", 
    x-init="
    {{-- height=conversationElement.scrollHeight;
    nextTick(()=>conversationElement.scrollTop = height) --}}

    @if($selectedConversation){    
    Echo.private('users.{{Auth()->User()->id}}')
        .notification((notification)=>{
            if(notification['type']== 'App\\Notifications\\MessageRead' && notification['conversation_id']== {{$this->selectedConversation->id?? 'null'}})
            {
alert('Messages marked as read');
                markAsRead=true;
            }
        });}
        @endif
    "
    class="flex flex-col h-full relative overflow-y-auto">

    
    {{-- <div class="bg-red-500 text-white p-2 text-center font-bold">
        Current Conversation ID: {{ $conversationId ?? 'NULL' }}
    </div> --}}

    @if($conversationId)

        <!-- Header -->
        <header class="border-b border-gray-150 rounded-b-xl p-3 flex items-center gap-3 bg-white">
            @if($selectedConversation && $selectedConversation->getReceiver())
                <img src="{{ $selectedConversation->getReceiver()->profile_photo_url ?? 'https://picsum.photos/200/200?random' }}"
                     class="w-10 h-10 rounded-full object-cover" />
            @else
                <img src="https://i.pravatar.cc/150?img=5" class="w-10 h-10 rounded-full" />
            @endif

            <div>
                <h6 class="font-semibold">
                    {{ $selectedConversation?->getReceiver()?->name ?? 'User' }}
                </h6>
                @php
                    $isOnline=$selectedConversation?->getReceiver()?->last_seen_at?->gt(now()->subMinutes(2)) ?? false;
                @endphp
                <span class="text-xs {{$isOnline ? 'text-green-500':'text-gray-400' }}">
{{$isOnline ? 'Online':'Offline'}}

                </span>
            </div>
        </header>

        <!-- Messages Area -->
        <main class="flex-1 overflow-y-auto p-4 space-y-4 bg-gradient-to-tr from-amber-100 via-amber-50 to-white" 
              >
            
            @if($loadedMessages && $loadedMessages->count() > 0)
                @foreach($loadedMessages as $message)
                    @if($message->sender_id == auth()->id())
                        <!-- SENDER MESSAGE -->
                        <div class="flex justify-end">
                            <div class="flex flex-col items-end max-w-xs lg:max-w-md">
                                <div class="bg-lime-200 text-black p-4 rounded-2xl rounded-tr-none shadow text-base">
                                    {{ $message->body }}
                                </div>
                                
                                <!-- Ticks -->
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-[10px] text-gray-400 mr-1">
                                        {{ $message->created_at->format('g:i a') }}
                                    </span>

                                    @if($message->isRead())
                                        {{-- Double--}}
                                        <div x-data="{ read: true }" x-cloak>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#3B82F6" class="bi bi-check2-all" viewBox="0 0 16 16">
                                                <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                                <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                            </svg>
                                        </div>
                                    @else
                                        {{-- Single --}}
                                        <div x-data="{ read: false }" x-cloak>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#9CA3AF" class="bi bi-check2" viewBox="0 0 16 16">
                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <!-- My Avatar -->
                            {{-- <img src="{{ auth()->user()->profile_photo_url }}" 
                                 class="w-8 h-8 rounded-full object-cover ml-2 self-end mb-1" /> --}}
                        </div>
                    @else
                        <!-- RECEIVER-->
                        <div class="flex items-start gap-2">
                            @if($message->sender)
                                <img src="{{ $message->sender->profile_photo_url ?? 'https://picsum.photos/200/200?random' }}"
                                     class="w-8 h-8 rounded-full object-cover" />
                            @else
                                <img src="" class="w-8 h-8 rounded-full object-cover" />
                            @endif
                            
                            <div class="flex flex-col items-start max-w-xs lg:max-w-md">
                                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow text-sm">
                                    {{ $message->body }}
                                </div>
                                <span class="text-[10px] text-black mt-1">
                                    {{ $message->created_at->format('g:i a') }}
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center text-gray-400 text-sm mt-10">No messages yet. Say hello!</div>
            @endif

        </main>

        <!-- Input -->
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
                                class="col-span-2 bg-lime-200 text-black rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition" 
                                type='submit'>
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </footer>

    @else
        <div class="flex flex-col items-center justify-center h-full bg-gray-50 text-center p-6">
            <h3 class="text-xl font-bold text-gray-700">Welcome to Chat</h3>
            <p class="text-gray-500 mt-2">Please select a conversation.</p>
        </div>
    @endif

</div>