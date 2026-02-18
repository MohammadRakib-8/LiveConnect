<div
   x-data="{type:'all'}" 
   class="flex flex-col transition-all h-full overflow-hidden">
     
    <header class="px-3 z-10 bg-white sticky top-0 w-full py-2">
        <div class="border-b justify-between flex items-center pb-2">
            <div class="flex items-center gap-2">
                 <h5 class="font-extrabold text-2xl">Chats</h5>
            </div>
        </div>
        <div class="flex gap-3 items-center overflow-x-scroll p-2 bg-white">
            <button @click="type='all'" :class="{'bg-blue-100 border-0 text-black':type=='all'}" class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1 lg:py-2.5 border">
                All
            </button>
            <button @click="type='deleted'" :class="{'bg-blue-100 border-0 text-black':type=='deleted'}" class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1 lg:py-2.5 border">
                Deleted
            </button>
        </div>
    </header>

    <main class="overflow-y-scroll grow h-full relative">
        <ul class="p-2 grid w-full spacey-y-2">
            @if ($conversations)
                @foreach ($conversations as $key => $conversation)
                   <li
                        id="conversation-{{$conversation->id}}"
                        wire:key="{{$conversation->id}}"
                        wire:click="selectConversation({{$conversation->id}})"
                        class="py-3 hover:bg-gray-50 rounded-2xl dark:hover:bg-gray-700/70 transition-colors duration-150 flex gap-4 relative w-full cursor-pointer px-2 {{$conversation->id==$selectedConversation?->id ? 'bg-gray-100/70':''}}"
                    >
                        <div class="shrink-0">
                            <x-avatar src="https://picsum.photos/200/200?random={{$key}}" />
                        </div>

                        <aside class="grid grid-cols-12 w-full">
                            <!-- MAIN CONTAINER -->
                            <div class="col-span-11 border-b pb-2 border-gray-200 relative flex flex-col justify-center gap-1 p-1 w-full">

                                <!--Name/Time -->
                                <div class="flex justify-between w-full items-center">
                                    <h6 class="truncate font-medium tracking-wider text-gray-900">
                                        {{ $conversation->getReceiver()->name }}
                                    </h6>
                                    <small class="text-gray-700 text-xs">
                                        {{ $conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans() }} 
                                    </small>
                                </div>
                            
                                <div class="flex items-center gap-1 w-full">
                                    
                                    @if($lastMessage = $conversation->messages->last())
                                        @if($lastMessage->sender_id == auth()->id())
                                            <div class="flex items-center shrink-0">
                                                @if($lastMessage->isRead())
                                                    {{-- Double--}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#9CA3AF" class="bi bi-check2-all" viewBox="0 0 16 16">
                                                        <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                                        <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                                    </svg>
                                                @else
                                                    {{-- Single--}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#9CA3AF" class="bi bi-check2" viewBox="0 0 16 16">
                                                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Message Text -->
                                    <div class="truncate text-sm text-gray-500 font-normal">
                                        @if($conversation->messages && $conversation->messages->last())
                                            {{ $conversation->messages->last()->body }}
                                        @else
                                            <span class="italic text-gray-400">Start a conversation...</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Unread Badge -->
                                @if($conversation->unreadMessagesCount() > 0)
                                    <span class="absolute bottom-0 right-0 bg-red-500 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-sm z-20">
                                        {{ $conversation->unreadMessagesCount() }}
                                    </span>
                                @endif
                                
                            </div>
                        </aside>
                    </li>
                @endforeach
            @endif
        </ul>
    </main>
    <script>
        console.log(@json($selectedConversation));
    </script>
</div>