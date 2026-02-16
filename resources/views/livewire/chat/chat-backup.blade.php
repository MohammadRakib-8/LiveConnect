<div class="fixed h-full flex bg-white border lg:shadow-sm overflow-hidden inset-0 lg:top-16 lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">

    <div class="hidden lg:flex relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border">
        <livewire:chat.chat-list :selectedConversation="$selectedConversation" />
    </div>

    <div class="grid w-full border-l h-full relative overflow-y-auto" style="contain:content">
        
        <!-- Pass conversationId, and use the ID as the key to force a refresh -->
        <livewire:chat.chat-box 
            :conversationId="$selectedConversation?->id" 
            :key="$selectedConversation?->id ?? 'empty'" 
        />

    </div>

</div>