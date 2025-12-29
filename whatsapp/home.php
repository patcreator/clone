<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Web Clone</title>
    <!-- Tailwind CSS via CDN (for demo) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Flowbite CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <!-- Icons (Heroicons or similar for WhatsApp-like) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'whatsapp-bg': '#0b141a',
                        'whatsapp-sidebar': '#111b21',
                        'whatsapp-header': '#202c33',
                        'whatsapp-chat': '#0b141a',
                        'whatsapp-incoming': '#202c33',
                        'whatsapp-outgoing': '#005c4b',
                        'whatsapp-text': '#e9edef',
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full bg-whatsapp-bg text-whatsapp-text flex">
    <div class="flex w-full max-w-7xl mx-auto h-screen">
        <!-- Left Sidebar: Chats List -->
        <div class="w-96 bg-whatsapp-sidebar flex flex-col">
            <!-- Header -->
            <div class="h-16 bg-whatsapp-header flex items-center justify-between px-4">
                <h1 class="text-xl font-medium">WhatsApp</h1>
                <div class="flex gap-6 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                </div>
            </div>
            <!-- Search and Filters -->
            <div class="px-3 py-2">
                <input type="text" placeholder="Search or start a new chat" class="w-full bg-whatsapp-header rounded-lg px-4 py-3 text-sm focus:outline-none" />
                <div class="flex gap-4 mt-3 text-sm text-gray-400">
                    <button class="pb-2 border-b-2 border-green-500">All</button>
                    <button class="pb-2">Unread</button>
                    <button class="pb-2">Favorites</button>
                    <button class="pb-2">Groups</button>
                </div>
            </div>
            <!-- Chats List -->
            <div class="flex-1 overflow-y-auto">
                <!-- Chat Item Example -->
                <div class="flex items-center px-3 py-3 hover:bg-whatsapp-header cursor-pointer border-b border-black/10">
                    <img src="https://via.placeholder.com/48" alt="Profile" class="w-12 h-12 rounded-full" />
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between">
                            <h3 class="font-medium">+250 793 094 231</h3>
                            <span class="text-xs text-gray-400">19:36</span>
                        </div>
                        <p class="text-sm text-gray-400 truncate">ufite akanya 😊</p>
                    </div>
                </div>
                <!-- More chat items... (repeat similar structure) -->
                <div class="flex items-center px-3 py-3 hover:bg-whatsapp-header cursor-pointer border-b border-black/10">
                    <img src="https://via.placeholder.com/48" alt="Profile" class="w-12 h-12 rounded-full" />
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between">
                            <h3 class="font-medium">L5 Sod Student</h3>
                            <span class="text-xs text-gray-400">19:17</span>
                        </div>
                        <p class="text-sm text-gray-400 truncate">The group "L5 student" was re...</p>
                    </div>
                </div>
                <!-- Add other chats from the screenshot similarly -->
            </div>
        </div>

        <!-- Right Chat Area -->
        <div class="flex-1 flex flex-col bg-whatsapp-chat">
            <!-- Chat Header -->
            <div class="h-16 bg-whatsapp-header flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <img src="https://via.placeholder.com/40" alt="Contact" class="w-10 h-10 rounded-full" />
                    <div>
                        <h2 class="font-medium">+250 793 094 231</h2>
                        <p class="text-xs text-gray-400">last seen today at 19:32</p>
                    </div>
                </div>
                <div class="flex gap-6 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                </div>
            </div>

            <!-- Messages Area (using Flowbite-like structure for bubbles) -->
            <div class="flex-1 overflow-y-auto p-6 bg-[url('https://via.placeholder.com/1920x1080?text=WhatsApp+Pattern')] bg-repeat">
                <p class="text-center text-xs text-gray-500 my-4">Use WhatsApp on your phone to see older messages from before 12/21/2024.</p>
                
                <!-- Incoming Message -->
                <div class="flex items-end gap-3 max-w-lg">
                    <div class="flex flex-col">
                        <div class="bg-whatsapp-incoming rounded-lg px-4 py-2">
                            <p class="text-sm">Waiting for this message. This may take a while. Learn more</p>
                        </div>
                        <span class="text-xs text-gray-500 mt-1">20:46</span>
                    </div>
                </div>

                <!-- Date Separator -->
                <p class="text-center text-xs text-gray-500 my-6">Yesterday</p>

                <!-- Outgoing Message -->
                <div class="flex justify-end items-end gap-3 max-w-lg ml-auto">
                    <div class="flex flex-col items-end">
                        <div class="bg-whatsapp-outgoing rounded-lg px-4 py-2">
                            <p class="text-sm">You urahari se</p>
                        </div>
                        <span class="text-xs text-gray-500 mt-1">20:56</span>
                    </div>
                </div>

                <!-- Incoming -->
                <div class="flex items-end gap-3 max-w-lg mt-4">
                    <div class="flex flex-col">
                        <div class="bg-whatsapp-incoming rounded-lg px-4 py-2">
                            <p class="text-sm">Ijoro ryiza imana ikurinde</p>
                        </div>
                        <span class="text-xs text-gray-500 mt-1">20:57</span>
                    </div>
                </div>

                <!-- Outgoing with emoji -->
                <div class="flex justify-end items-end gap-3 max-w-lg ml-auto mt-4">
                    <div class="flex flex-col items-end">
                        <div class="bg-whatsapp-outgoing rounded-lg px-4 py-2">
                            <p class="text-sm">naringiye kuguhamaagara! uraryamwe kano kanya 😭</p>
                        </div>
                        <span class="text-xs text-gray-500 mt-1">21:18 ✓✓</span>
                    </div>
                </div>

                <!-- Date Separator -->
                <p class="text-center text-xs text-gray-500 my-6">Today</p>

                <!-- Outgoing -->
                <div class="flex justify-end items-end gap-3 max-w-lg ml-auto mt-4">
                    <div class="flex flex-col items-end">
                        <div class="bg-whatsapp-outgoing rounded-lg px-4 py-2">
                            <p class="text-sm">naringiye kuguhamaagara! uraryamwe kano kanya 😭</p>
                            <p class="text-sm mt-2">Blessed morning waramutse gtx</p>
                        </div>
                        <span class="text-xs text-gray-500 mt-1">06:49</span>
                    </div>
                </div>

                <!-- Incoming -->
                <div class="flex items-end gap-3 max-w-lg mt-4">
                    <div class="flex flex-col">
                        <div class="bg-whatsapp-incoming rounded-lg px-4 py-2">
                            <p class="text-sm">nexa woe se?</p>
                        </div>
                        <span class="text-xs text-gray-500 mt-1">13:12 ✓✓</span>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="h-16 bg-whatsapp-header flex items-center px-4 gap-4">
                <button class="text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
                <input type="text" placeholder="Type a message" class="flex-1 bg-whatsapp-sidebar rounded-full px-4 py-3 text-sm focus:outline-none" />
                <button class="text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.586 6.586a6 6 0 008.454 8.454l.828-.828"/></svg>
                </button>
                <button class="text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
        </div>
    </div>
</body>
</html>