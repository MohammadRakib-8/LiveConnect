<div
   x-data="{type:'all'}" 
   class="flex flex-col h-full w-full bg-white overflow-hidden">
     
    <!-- Header -->
    <header class="px-5 z-10 bg-white/90 backdrop-blur-md sticky top-0 w-full py-4 border-b border-gray-100 rounded-b-2xl">
        <div class="flex items-center justify-between mb-4">
            <h5 class="font-extrabold text-2xl text-gray-800 tracking-tight">Chats</h5>
            <!-- Optional: Add an icon button here later -->
        </div>

        <!-- Filters -->
        <div class="flex gap-3 items-center">
            <button @click="type='all'" :class="{'bg-black text-black shadow-lg transform scale-105':type=='all'}" class="transition-all duration-300 ease-out inline-flex justify-center items-center rounded-full px-5 py-2 text-sm font-medium border border-transparent bg-gray-100 hover:bg-gray-200 text-gray-600">
                All
            </button>
            <button @click="type='deleted'" :class="{'bg-black text-black shadow-lg transform scale-105':type=='deleted'}" class="transition-all duration-300 ease-out inline-flex justify-center items-center rounded-full px-5 py-2 text-sm font-medium border border-transparent bg-gray-100 hover:bg-gray-200 text-gray-600">
                Deleted
            </button>
        </div>
    </header>

    <!-- Main List -->
    <main class="overflow-y-scroll flex-1 border-t border-1 rounded-t-2xl  border-gray-300 p-10 space-y-1  bg-amber-50 ">
        @if ($conversations)
            @foreach ($conversations as $key => $conversation)
                <li
                    wire:key="{{$conversation->id}}"
                    wire:click="selectConversation({{$conversation->id}})"
                    class="group flex items-center gap-4 p-1 rounded-xl cursor-pointer transition-all duration-200 hover:bg-gray-50 hover:shadow-sm border-b border-2 {{ $conversation->id == $selectedConversation?->id ? 'bg-blue-50/80 shadow-sm ring-1 ring-blue-100' : '' }}"
                >
                <div class="relative shrink-0">
                        <div class="w-14 h-14 rounded-full overflow-hidden ring-2 ring-white shadow-md">
                            <img src="https://picsum.photos/200/200?random={{$key}}" class="w-full h-full object-cover" alt="Avatar">
                        </div>
                        <!-- Online Status -->
                        @if($conversation->getReceiver()->is_online ?? false)
                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        
                        <!--  Name & Time -->
                        <div class="flex justify-between items-baseline mb-0.5">
                            <h6 class="text-base font-bold text-gray-900 truncate">
                                {{ $conversation->getReceiver()->name }}
                            </h6>
                            <span class="text-xs font-medium text-gray-400 whitespace-nowrap ml-2">
                                {{ $conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans() }} 
                            </span>
                        </div>
                        
                        <!-- Tick & Message -->
                        <div class="flex items-center gap-1.5">
                            
                            <!-- Tick Icon -->
                            @if($lastMessage = $conversation->messages->last())
                                @if($lastMessage->sender_id == auth()->id())
                                    <div class="text-gray-400 shrink-0 flex items-center">
                                        @if($lastMessage->isRead())
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="#3B82F6">
                                                <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                                <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="currentColor">
                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                @endif
                            @endif

                            <!-- Message Text -->
                            <p class="text-sm text-gray-500 truncate group-hover:text-gray-700 transition-colors">
                                @if($conversation->messages && $conversation->messages->last())
                                    {{ $conversation->messages->last()->body }}
                                @else
                                    <span class="italic text-gray-400 text-xs">Start a conversation...</span>
                                @endif
                            </p>
                        </div>

                    </div>

                    <!-- Unread Badge -->
                    @if($conversation->unreadMessagesCount() > 0)
                        <div class="shrink-0 ml-1">
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-blue-600 rounded-full shadow-sm ring-2 ring-white">
                                {{ $conversation->unreadMessagesCount() }}
                            </span>
                        </div>
                    @endif
                </li>
            @endforeach
        @else
            <div class="text-center mt-10 text-gray-400">
                <p>No conversations found.</p>
            </div>
        @endif
    </main>
    
    <script>
        console.log(@json($selectedConversation));
    </script>
</div>