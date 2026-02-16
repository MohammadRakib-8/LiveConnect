<div class="flex flex-col h-full">
     <!-- ADD THIS LINE FOR DEBUGGING -->
    <div class="bg-red-500 text-white p-2 text-center font-bold">
        Current Conversation ID: {{ $conversationId ?? 'NULL' }}
    </div>
{{-- <pre>
{{ dump($selectedConversation) }}
</pre> --}}

    <!-- Chat Header -->
    <header class="border-b p-3 flex items-center gap-3 bg-white">
        <img src="https://i.pravatar.cc/150?img=5"
             class="w-10 h-10 rounded-full" />

        <div>
            <h6 class="font-semibold">{{$selectedConversation?->getReceiver()?->name}}</h6>
            <span class="text-xs text-green-500">Online</span>
        </div>
    </header>

    <!-- Messages Area -->
    <main class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">

        <!-- Receiver Message -->
        <div class="flex items-start gap-2">
            <img src="https://i.pravatar.cc/150?img=5"
                 class="w-8 h-8 rounded-full" />

            <div class="bg-white p-3 rounded-2xl shadow text-sm">
                Hello 👋
            </div>
        </div>

        <!-- Sender Message -->
        <div class="flex justify-end">
            <div class="bg-blue-500 text-white p-3 rounded-2xl shadow text-sm">
                Hi! How are you?
            </div>
        </div>

    </main>

    <!-- Message Input -->
    <footer class="border-t p-3 bg-white">

        <form class="flex gap-2">

            <input
                type="text"
                placeholder="Type message..."
                class="flex-1 border rounded-full px-4 py-2 focus:outline-none"
            />

            <button
                class="bg-blue-500 text-white px-5 rounded-full">
                Send
            </button>

        </form>

    </footer>

</div>
