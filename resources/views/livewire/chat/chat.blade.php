{{-- 
<div class="flex h-[75vh] bg-white border shadow-sm rounded-lg overflow-hidden">

    <div class="hidden lg:flex w-[350px] border-r overflow-y-auto">
        <livewire:chat.chat-list/>
    </div>

    <div class="flex flex-col flex-1 overflow-hidden">
        <livewire:chat.chat-box/>
    </div>

</div>

 --}}
<div class="fixed h-full w-full flex border-s border-gray-200 bg-white border lg:shadow-sm overflow-hidden inset-0 lg:top-16 lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">

    <div class="hidden lg:flex relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border">
        <livewire:chat.chat-list :selectedConversation="$selectedConversation" />
    </div>

    <div class="grid w-full border-l h-full relative overflow-y-auto" style="contain:content">
        
        <livewire:chat.chat-box 
            :conversationId="$selectedConversation?->id" 
            :key="$selectedConversation?->id ?? 'empty'" 
        />

    </div>

</div>