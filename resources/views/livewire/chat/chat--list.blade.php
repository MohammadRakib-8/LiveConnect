<div x-data="{ type: 'all' }" class="flex flex-col transition-all h-full overflow-hidden">

    <!-- Header -->
    <header class="px-3 z-10 bg-white sticky top-0 w-full py-2">

        <div class="border-b justify-between flex items-center pb-2">

            <div class="flex items-center gap-2">
                <h5 class="font-extrabold text-2xl">Chats</h5>
            </div>

            <button>
                <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </button>

        </div>

        <!-- Filters -->
        <div class="flex gap-3 items-center overflow-x-scroll p-2 bg-white">

            <button
                @click="type='all'"
                :class="{'bg-blue-100 border-0 text-black': type === 'all'}"
                class="inline-flex justify-center items-center rounded-full text-xs font-medium px-4 py-2 border">
                All
            </button>

            <button
                @click="type='deleted'"
                :class="{'bg-blue-100 border-0 text-black': type === 'deleted'}"
                class="inline-flex justify-center items-center rounded-full text-xs font-medium px-4 py-2 border">
                Deleted
            </button>

        </div>

    </header>


    <!-- Chat List -->
    <main class="overflow-y-scroll grow h-full relative">

        <ul class="p-2 grid w-full space-y-2">

            <!-- Chat Item -->
            <li class="py-3 hover:bg-gray-50 rounded-2xl transition flex gap-4 relative w-full cursor-pointer px-2">

                <!-- Avatar -->
                <a href="#" class="shrink-0">
                    <img src="/images/default-avatar.png"
                         class="w-12 h-12 rounded-full object-cover"/>
                </a>

                <aside class="grid grid-cols-12 w-full">

                    <a href="#" class="col-span-11 border-b pb-2 border-gray-200 overflow-hidden truncate w-full p-1">

                        <!-- Name + Time -->
                        <div class="flex justify-between items-center">
                            <h6 class="truncate font-medium text-gray-900">
                                User Name
                            </h6>
                            <span class="text-xs text-gray-400">
                                2m ago
                            </span>
                        </div>

                        <!-- Message -->
                        <div class="flex gap-x-2 items-center">

                            <!-- Tick Icon -->
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7z"/>
                                </svg>
                            </span>

                            <p class="grow truncate text-sm font-light">
                                Last message preview goes here...
                            </p>

                            <!-- Unread Count -->
                            <span class="font-bold px-2 text-xs rounded-full bg-blue-500 text-white">
                                3
                            </span>

                        </div>

                    </a>

                    <!-- Dropdown -->
                    <div class="col-span-1 flex flex-col text-center my-auto">

                        <x-dropdown align="right" width="48">

                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-6 h-6 text-gray-700"
                                         fill="currentColor"
                                         viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-full p-1">

                                    <button class="flex w-full px-4 py-2 text-sm text-gray-500 hover:bg-gray-100">
                                        View Profile
                                    </button>

                                    <button
                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        wire:click="deleteConversation"
                                        class="flex w-full px-4 py-2 text-sm text-gray-500 hover:bg-gray-100">
                                        Delete
                                    </button>

                                </div>
                            </x-slot>

                        </x-dropdown>

                    </div>

                </aside>

            </li>

        </ul>

    </main>

</div>
