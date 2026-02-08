<div x-data="{ type: 'all' }" class="flex flex-col h-full">

    <!-- Header -->
    <header class="p-3 border-b bg-white sticky top-0">
        <h5 class="font-bold text-xl">Chats</h5>
    </header>

    <!-- Filter Buttons -->
    <div class="flex gap-2 p-2 border-b">
        <button @click="type='all'"
            :class="{'bg-blue-500 text-white': type==='all'}"
            class="px-3 py-1 rounded-full border text-sm">
            All
        </button>

        <button @click="type='deleted'"
            :class="{'bg-blue-500 text-white': type==='deleted'}"
            class="px-3 py-1 rounded-full border text-sm">
            Deleted
        </button>
    </div>

    <!-- Conversation List -->
    <main class="flex-1 overflow-y-auto p-2 space-y-2">

        <!-- Chat Item -->
        <div class="flex gap-3 p-2 hover:bg-gray-100 rounded-xl cursor-pointer">

            <img src="https://i.pravatar.cc/150?img=3"
                 class="w-12 h-12 rounded-full" />

            <div class="flex-1">
                <div class="flex justify-between">
                    <h6 class="font-semibold">User Name</h6>
                    <span class="text-xs text-gray-400">2m</span>
                </div>

                <p class="text-sm text-gray-500 truncate">
                    Last message preview...
                </p>
            </div>

            <span class="bg-blue-500 text-white text-xs px-2 rounded-full">
                3
            </span>

        </div>

    </main>

</div>
