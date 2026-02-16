<div
   x-data="{type:'all'}" 
   class="flex flex-col transition-all h-full overflow-hidden">
     
    <header class="px-3 z-10 bg-white sticky top-0 w-full py-2">
        <div class="border-b justify-between flex items-center pb-2">
            <div class="flex items-center gap-2">
                 <h5 class="font-extrabold text-2xl">Chats</h5>
            </div>
        </div>

        {{-- Filters --}}
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
    <!-- Just use a div or span, NOT an <a> tag -->
    <div class="shrink-0">
        <x-avatar src="https://picsum.photos/200/200?random={{$key}}" />
    </div>

    <aside class="grid grid-cols-12 w-full">
        <!-- Also remove the <a> wrapper here -->
        <div class="col-span-11 border-b pb-2 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1">
            <div class="flex justify-between w-full items-center">
                <h6 class="truncate font-medium tracking-wider text-gray-900">
                    {{$conversation->getReceiver()->name}}
                </h6>
                <small class="text-gray-700">
                    {{$conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans()}} 
                </small>
            </div>
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