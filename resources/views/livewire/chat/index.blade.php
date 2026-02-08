<div class="flex h-[75vh] bg-white border shadow-sm rounded-lg overflow-hidden">

    <!-- Sidebar -->
    <div class="hidden lg:flex w-[350px] border-r overflow-y-auto">
        <livewire:chat.chat-list/>
    </div>

    <!-- Chat Box -->
    <div class="flex flex-col flex-1 overflow-hidden">
        <livewire:chat.chat-box/>
    </div>

</div>
