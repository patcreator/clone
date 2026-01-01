<?php include_once "app/pages/sections/h.php";?>
    <!-- main page -->
     
        <!-- Main Content -->
        <main class="flex flex-1">
            <!-- Chats Sidebar -->
            <div id="new-chat-menu"></div>
            <div id="business-tools-container"></div>
            <aside id="messages-sidebar" class="w-80 border-r border-gray-800 bg-gray-900 flex flex-col h-full transition-colors duration-300 relative">
               
            </aside>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col bg-gray-900 transition-colors duration-300">
                <?php 
                    if (isset($_GET['r']) && !empty($_GET['r']) && is_numeric($_GET['r']) && isset($_GET['s']) && !empty($_GET['s']) && is_numeric($_GET['s'])) {
                        $not_me = $_GET['r'] == $id ? $_GET['s'] : $_GET['r'];
                        $sender = $_GET['s'];
                        $receiver = $_GET['r'];
                        $messages = executeQuery("SELECT *,m.id as mid FROM message m inner join users u on u.id=m.sender where m.sender = $sender and m.receiver = $receiver or m.sender = $receiver and m.receiver = $sender ORDER by m.created_at asc")['data'];
                        $not_me_data = executeQuery("SELECT * FROM users where id = '$not_me' limit 1")['data'][0];
                ?>
                <!-- Chat Header -->
                <div class="border-b border-gray-800 p-4 flex justify-between items-center transition-colors duration-300 cursor-pointer">
                    <div class="flex items-center">
                        <img src="<?= $path ?>app/system/filemanager/images/avatars/<?= empty($not_me_data['image']) ? 'default.png' : $not_me_data['image'] ?>" class="w-10 h-10 rounded-full object-cover mr-3">
                        <div>
                            <h3 class="font-semibold"><?= $not_me_data['username'] ?></h3>
                            <p class="text-sm text-gray-400">Click to open user information</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="w-10 h-10 rounded-full hover:bg-gray-800 flex items-center justify-center transition-colors duration-300">
                            <i class="fas fa-video"></i>
                        </button>
                        <button class="w-10 h-10 rounded-full hover:bg-gray-800 flex items-center justify-center transition-colors duration-300">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="w-10 h-10 rounded-full hover:bg-gray-800 flex items-center justify-center transition-colors duration-300">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <div class="relative inline-block text-left">
                          <!-- Dropdown trigger -->
                          <button onclick="getPart('in-chat-menu','#chatOptionsDropdown')" 
                            id="chatOptionsButton"
                            data-dropdown-toggle="chatOptionsDropdown"
                            type="button"
                            class="inline-flex items-center justify-center p-2 px-3 m-1 text-sm text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          >
                            <i class="fa fa-ellipsis-v"></i>
                          </button>

                          <!-- Dropdown menu -->
                          <div
                            id="chatOptionsDropdown"
                            class="z-10 hidden w-56 bg-white dark:bg-gray-700 rounded-2xl shadow-lg"
                          >
                          </div>
                        </div>

                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    <?php foreach ($messages as $msg) { 
                            $content = htmlspecialchars($msg['content'], ENT_QUOTES, 'UTF-8');
                            $content_type = htmlspecialchars($msg['content_type'], ENT_QUOTES, 'UTF-8');
                            $type = $content_type;
                            $status = htmlspecialchars($msg['status'], ENT_QUOTES, 'UTF-8');
                            $is_group = htmlspecialchars($msg['is_group'], ENT_QUOTES, 'UTF-8');
                            $deleted_by_sender = htmlspecialchars($msg['deleted_by_sender'], ENT_QUOTES, 'UTF-8');
                            $deleted_by_receiver = htmlspecialchars($msg['deleted_by_receiver'], ENT_QUOTES, 'UTF-8');
                        ?>
<?php if ($msg['sender'] == $id) { 
    if ($content_type == 'document') {
$content = preg_replace('/^document:/', '', $content);
 if (preg_match("/\.[a-zA-Z]+$/", $content, $matches)) $ext = trim($matches[0],'.');else $ext = '';
 $file = 'app/system/filemanager/uploads/documents/'.$content;
 $size = filesize($file);
 $size = round($size / 1024, 2);
 $size = $size . 'Kb';
 $icons = [
    "txt"=>'file-alt',
    "mp4"=>'file-video',
    "mp3"=>'note'
 ];
 $icon = $icons[$ext]??'file-alt';
echo <<<HTML
<div class="flex justify-end">
        <div class="message-bubble bg-indigo-500 text-white rounded-\2xl shadow-lg p-1 px-3 ">
        <!-- Document header with icon and info -->
        <div class="flex items-start space-x-4 mb-4">
            <!-- Document icon -->
            <div class="document-icon rounded-xl p-3 flex items-center justify-center text-white">
                <i class="fas fa-$icon text-white text-2xl"></i>
            </div>
            
            <!-- Document details -->
            <div class="flex-1">
                <h3 class="font-semibold text-white text-lg">{$content}</h3>
                <div class="flex items-center text-white space-x-3 mt-1">
                    <span class="flex items-center text-gray-200">
                        <span class="text-xs ">{$ext}</span>
                    </span>
                    <i class="text-2xs">-</i>
                    <span class="flex items-center text-gray-200">
                        <span class="text-xs ">{$size}</span>
                    </span>
                    <a href="$file" download="$content" class="inline-flex items-center  text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-xs px-3 py-1.5 focus:outline-none"><i class="fas fa-download p-2 bg-white rounded-full text-black"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;
}else{
    echo <<<HTML
    <div class="flex justify-end">
        <div class="chat-bubble-left p-3">
            $content
        </div>
    </div>
HTML;

                            }
                            ?>
                        <?php } else { ?>
                            <div class="flex justify-start">
                                <div class="chat-bubble-right p-3">
                                    <?= htmlspecialchars($msg['content']) ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <!-- Message Input -->
                <form method="POST" action="@api/api?action=send" class="p-4 border-t border-gray-800 transition-colors duration-300">
                    <div class="flex items-center bg-gray-800 rounded-full px-4 py-2 transition-colors duration-300">
                        <button type="button" id="attachmentButton" data-dropdown-toggle="attachmentDropdown" class="w-10 h-10 rounded-full hover:bg-gray-700 flex items-center justify-center transition-colors duration-300">
                            <i class="fas fa-plus"></i>
                        </button>
                        
                        <!-- Attachment Dropdown -->
                        <div id="attachmentDropdown" class="hidden z-10 bg-gray-800 divide-y divide-gray-700 rounded-lg shadow w-64 transition-colors duration-300" data-get-part='message-menu'>
                        </div>

                        <button type="button" id="emojiButton" class="w-10 h-10 rounded-full hover:bg-gray-700 flex items-center justify-center ml-2 transition-colors duration-300">
                            <i class="fas fa-smile"></i>
                        </button>

                        <div class="flex-1 mx-3">
                            <textarea name="content" rows="1" 

                                id="message-box"
                                class="w-full bg-transparent border-none focus:ring-0 dark:text-white resize-none transition-colors duration-300" 
                                placeholder="Type a message"
                                oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px'; 
                                document.getElementById('sendButton').classList.toggle('hidden', !this.value.trim());
                                document.getElementById('recordButton').classList.toggle('hidden', this.value.trim());"></textarea>
                        </div>

                        <input type="hidden" class="text-green-500" id="receiver" name="receiver" value="<?= $not_me ?>">
                        <input type="hidden" class="text-green-500" id="sender" name="sender" value="<?= $id ?>">
                        <input type="hidden" name="is_group" value="0">

                        <button type="button" id="recordButton" class="w-10 h-10 rounded-full bg-purple-600 hover:bg-purple-700 flex items-center justify-center transition-colors duration-300">
                            <i class="fas fa-microphone"></i>
                        </button>
                        
                        <button type="submit" id="sendButton" class="w-10 h-10 rounded-full bg-purple-600 hover:bg-purple-700 flex items-center justify-center ml-2 hidden transition-colors duration-300">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                <?php } else { ?>
                    <div data-get-part="welcome-message"></div>
                <?php } ?>
            </div>
        </main>
<?php include_once "app/pages/sections/f.php";?>