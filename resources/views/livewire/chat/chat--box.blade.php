<div class="fixed h-full flex bg-white border lg:shadow-sm overflow-hidden inset-0 lg:top-16 lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">

    <!-- Chat list (left panel) -->
    {{-- <div class="relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border p-4">
        <h5 class="text-xl font-bold mb-3">Chats</h5>
        <ul class="space-y-2">
            <li class="p-2 border rounded hover:bg-gray-100 cursor-pointer">User 1</li>
            <li class="p-2 border rounded hover:bg-gray-100 cursor-pointer">User 2</li>
            <li class="p-2 border rounded hover:bg-gray-100 cursor-pointer">User 3</li>
        </ul>
    </div> --}}

    <!-- Chat box / right panel -->
    <div class="grid w-full border-l h-full relative overflow-y-auto" style="contain:content">
        <div class="w-full h-full flex flex-col">

            <!-- Header -->
            <header class="w-full sticky top-0 z-10 bg-white border-b flex items-center gap-3 p-3">
                <div class="shrink-0">
                    <img src="https://source.unsplash.com/50x50?face" class="w-10 h-10 rounded-full" alt="Avatar">
                </div>
                <h6 class="font-bold truncate">Receiver Email</h6>
            </header>

            <!-- Messages -->
            <main id="conversation" class="flex flex-col gap-3 p-3 overflow-y-auto flex-grow">
                <div class="max-w-[85%] md:max-w-[78%] flex gap-2 relative mt-2 ml-auto">
                    <div class="flex flex-col text-white bg-blue-500/80 rounded-xl p-2.5">
                        <p>Hello! How are you?</p>
                        <span class="text-xs text-white ml-auto">10:30 am ✔✔</span>
                    </div>
                </div>

                <div class="max-w-[85%] md:max-w-[78%] flex gap-2 relative mt-2">
                    <div class="flex flex-col text-black bg-gray-100 rounded-xl p-2.5">
                        <p>I'm good, thanks!</p>
                        <span class="text-xs text-gray-500 ml-auto">10:31 am</span>
                    </div>
                </div>
            </main>

            <!-- Send message -->
            <footer class="shrink-0 bg-white border-t p-3">
                <div class="grid grid-cols-12 gap-2">
                    <input type="text" placeholder="Write your message here" class="col-span-10 bg-gray-100 rounded-lg p-2 outline-none">
                    <button class="col-span-2 bg-blue-500 text-white rounded-lg">Send</button>
                </div>
            </footer>

        </div>
    </div>
</div>
