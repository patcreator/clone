<?php 
    include_once 'app/auth/isAuth.php';
    include_once 'app/system/cogs/db.php';
    include_once 'app/system/cogs/functions.php';
    include_once 'app/api/db_helper.php';
    $path ='/clone/whatsapp/';
    $matches = [];
    if (preg_match("/(unread|favorite|group)/", $_SERVER['QUERY_STRING']??'', $matches)) $query_string =$matches[0]; else $query_string = '';
    // Check for theme preference in session or default to light
    if (!isset($_SESSION['theme'])) {
        $_SESSION['theme'] = 'light'; // Default to light mode
    }
    $currentTheme = $_SESSION['theme'];
?>
<?php 
    $id = $_SESSION['user_id'];
    $user = executeQuery("SELECT * FROM users WHERE id = $id")['data'];
    if (!$user) {
        session_destroy();
        header("location:login");
    }
    $profile = executeQuery("SELECT * FROM user_profile WHERE user_id = '$id'")['data'];
    $message = executeQuery("SELECT * FROM message where sender = $id or receiver = $id")['data'];
?>

<!DOCTYPE html>
<html class="<?php echo $currentTheme; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>URUGANIRIRO</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Tailwind + Flowbite -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>

<!-- Unicons -->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/7.4.47/css/materialdesignicons.min.css">

<!-- Dripicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dripicons/2.0.0/webfont.min.css">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        // Initialize theme before page loads to prevent flash
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />




    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $path ?>app/system/styles/styles.css">
    <style>
    .chat-bubble-right {
        position: relative;
        background-color: <?php echo $currentTheme === 'dark' ? '#374151' : '#e5e7eb'; ?>;
        color: <?php echo $currentTheme === 'dark' ? 'white' : '#111827'; ?>;
        border-radius: 1rem;
        border-bottom-left-radius: 0.25rem;
        max-width: 70%;
    }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 h-screen overflow-hidden transition-colors duration-300">
    <!-- Header -->
    <header  class="bg-gray-900 border-b border-gray-800 p-3 transition-colors duration-300">
        <div class="flex items-center">
            <i class="fas fa-comments text-2xl text-purple-600 mr-3"></i>
            <strong class="text-xl">URUGANIRIRO</strong>
        </div>
        <!-- Theme Toggle Switch (Floating at bottom right) -->
        <div class="theme-toggle absolute top-2 end-2">
            <button onclick="toggleTheme()" class="flex items-center w-14 h-8 rounded-full p-1 bg-gray-800 hover:bg-gray-700 transition-all duration-300 shadow-lg">
                <div class="absolute left-2">
                    <i class="fas fa-sun sun-icon text-yellow-300 text-sm"></i>
                </div>
                <div class="absolute right-2">
                    <i class="fas fa-moon moon-icon text-blue-300 text-sm"></i>
                </div>
                <div class="toggle-circle w-6 h-6 rounded-full bg-white shadow-md"></div>
            </button>
        </div>
    </header>

    <div id="right-sidebar" class="fixed border border-gray-800 bg-gray-900 h-[80vh] w-2/3 end-0 z-50 hidden">
        <!-- Header -->
        <header class="bg-gray-800 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button class="text-white" onclick="$('#right-sidebar').slideUp()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h1 class="text-xl font-medium" id="right-sidebar-title">Contact info</h1>
            </div>
        </header>
        <div id="right-sidebar-content" class="body overflow-y-auto h-full">
            
        </div>
    </div>


    <div class="flex h-[calc(100vh-64px)]">
        <!-- Left Sidebar -->
        <div class="w-16 bg-gray-900 border-r border-gray-800 flex flex-col items-center py-4 justify-between transition-colors duration-300" id="sm-sidebar"></div>

        <!-- Main Content -->
        <main class="flex flex-1">
            <!-- Chats Sidebar -->
            <div id="new-chat-menu"></div>
            <div id="business-tools-container"></div>
            <aside class="w-80 border-r border-gray-800 bg-gray-900 flex flex-col h-full transition-colors duration-300 relative">
                <div class = "aside-contents max-h-[90vh] overflow-y-auto" id="aside-contents">
                <!-- Chats Header -->
                <div class="p-4 border-b border-gray-800 flex justify-between items-center transition-colors duration-300">
                    <h5 class="text-lg font-semibold">Chats</h5>
                    <div class="flex space-x-2">
                        <button onclick="getPart('new-chat-menu')" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-gray-700 flex items-center justify-center transition-colors duration-300">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button  onclick="getPart('sidebar-menu','#chatsDropdown')" id="dropdownMenuButton" data-dropdown-toggle="chatsDropdown" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-gray-700 flex items-center justify-center transition-colors duration-300">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div id="chatsDropdown" class="hidden z-10 bg-gray-800 divide-y divide-gray-700 rounded-lg shadow w-64 transition-colors duration-300">
                        </div>
                    </div>
                </div>

                <!-- Search Box -->
                <div class="p-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-500"></i>
                        </div>
                        <input type="text" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-100 text-sm rounded-full focus:ring-purple-600 focus:border-purple-600 block w-full pl-10 p-2.5 transition-colors duration-300" placeholder="Search or start a new chat">
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="px-4 pb-3 flex space-x-2 overflow-x-auto">
                    <a href="<?= $path ?>" class="px-2 py-1 text-sm rounded-full transition-colors duration-300 <?= (!isset($_GET['favorite']) && !isset($_GET['group']) && !isset($_GET['unread'])) ? 'bg-gray-700 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' ?>">
                        All
                    </a>
                    <a href="<?= $path ?>?unread" class="px-2 py-1 text-sm rounded-full transition-colors duration-300 <?= isset($_GET['unread']) ? 'bg-gray-700 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' ?>">
                        Unread
                    </a>
                    <a href="<?= $path ?>?favorite" class="px-2 py-1 text-sm rounded-full transition-colors duration-300 <?= isset($_GET['favorite']) ? 'bg-gray-700 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' ?>">
                        Favorites
                    </a>
                    <a href="<?= $path ?>?group" class="px-2 py-1 text-sm rounded-full transition-colors duration-300 <?= isset($_GET['group']) ? 'bg-gray-700 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' ?>">
                        Groups
                    </a>
                </div>

                <!-- Here getPart not-internet if nessesaly -->
                <div id="no-internet"></div>

                <!-- Chats List -->
                <div class="flex-1 overflow-y-auto px-2">
                    <?php 
                        if (isset($_GET['unread'])) {
                            $sql = "SELECT *, m.id as mid, count(*) as nbsent FROM message m inner join users u on u.id=m.sender where m.receiver = $id and m.status in('pending','sent') group by m.sender";
                            $msg_txt = "Messages";
                            $sent_by_me = '';
                        } elseif (isset($_GET['favorite'])) {
                            $sql = "SELECT *,m.id as mid FROM favorites f inner join message m on f.item_id=m.id inner join users u on m.receiver = u.id where m.sender = $id or m.receiver = $id";
                            $msg_txt = "Favorites";
                            $sent_by_me = '';
                        } elseif (isset($_GET['group'])) {
                            $sql = "SELECT *,m.id as mid FROM message m inner join users u on u.id=m.sender where m.sender = $id and m.is_group = 1";
                            $msg_txt = "Groups";
                            $sent_by_me = '';
                        } else {
                            $sql = "SELECT *, m.id as mid, count(*) as nbsent FROM message m inner join users u on u.id=m.sender where m.receiver = $id and m.status = 'pending' group by m.sender";
                            $msg_txt = "Messages";
                            $sent_by_me = executeQuery("SELECT *, m.id as mid FROM message m inner join users u on u.id=m.receiver where m.sender = $id group by m.receiver ")['data'];
                        }
                        $exclude = [];
                        $data = executeQuery($sql)['data'];
                        if (empty($data) && (isset($_GET['favorite']) || isset($_GET['group']) || isset($_GET['unread']))) {
                            echo '<div class="mx-4 p-3 bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-red-400 transition-colors duration-300">' . $msg_txt . ' not found</div>';
                        }
                    ?>
                    
                    <?php foreach ($data as $item) { ?>
                    <a href="<?= $path ?>?r=<?= $item['receiver'] ?>&s=<?= $item['sender'] ?>&<?= $query_string ?>" class="flex items-center p-3 dark:hover:bg-gray-800  hover:bg-gray-200 rounded-xl mb-1 transition-colors duration-300 <?= ([$item['receiver'],$item['sender']] == [$_GET['r']??0,$_GET['s']??0]) ? 'bg-gray-800' : '' ?>">
                        <img src="<?= $path ?>app/system/filemanager/images/avatars/<?= empty($item['image']) ? 'default.png' : $item['image'] ?>" class="w-12 h-12 rounded-full object-cover mr-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-semibold truncate"><?= $item['username'] ?></p>
                                <span class="text-xs text-purple-400"><?= date("h:i", strtotime($item['created_at'])) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-400 truncate"><?= strlen($item['content']) > 20 ? substr($item['content'], 0, 20) . '...' : $item['content'] ?></p>
                                <?php if (isset($item['nbsent']) && $item['nbsent'] > 0) { ?>
                                <span class="bg-purple-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"><?= $item['nbsent'] ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                    <?php $exclude[] = [$item['receiver'],$item['sender']]; } ?>
                    
                    <?php 
                    if (!isset($_GET['favorite']) && !isset($_GET['group']) && !isset($_GET['unread'])) {
                        foreach ($sent_by_me as $item) { 
                            if (in_array([$item['sender'],$item['receiver']], $exclude)) continue;
                    ?>
                    <a href="<?= $path ?>?r=<?= $item['receiver'] ?>&s=<?= $item['sender'] ?>" class="flex items-center p-3 dark:hover:bg-gray-800 hover:bg-gray-200 rounded-xl mb-1 transition-colors duration-300 <?= ([$item['receiver'],$item['sender']] == [$_GET['r']??0,$_GET['s']??0]) ? 'bg-gray-800' : '' ?>">
                        <img src="<?= $path ?>app/system/filemanager/images/avatars/<?= empty($item['image']) ? 'default.png' : $item['image'] ?>" class="w-12 h-12 rounded-full object-cover mr-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-semibold truncate"><?= $item['username'] ?></p>
                                <span class="text-xs text-purple-400"><?= date("h:i", strtotime($item['created_at'])) ?></span>
                            </div>
                            <p class="text-sm text-gray-400 truncate"><?= strlen($item['content']) > 20 ? substr($item['content'], 0, 20) . '...' : $item['content'] ?></p>
                        </div>
                    </a>
                    <?php }} ?>
                </div>
               </div>
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
    </div>
<!-- Emoji Picker (Hidden by default) -->
<div id="emojiPicker" class="emoji-picker hidden bg-white shadow dark:bg-gray-800"></div>
<div id="upload-document"></div>
<div id="photo-video-modal-container"></div>
<div id="ads-container"></div>
    <!-- Flowbite JS -->
<script src="<?= $path ?>app/system/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    const uiStack = [];
$(document).ready(function () {
    getPart('sm-sidebar','#sm-sidebar');
    getPart('upload-document','#upload-document');
    getPart('photo-video-modal','#photo-video-modal-container');
    // getPart('business-tools/ads','#ads-container');
    $("[data-get-part]").each(function () {
        let part = $(this).data('get-part');
        getPart(part,this);
    });

});

    function getPart(part, element = null) {
    if (element == null) {element = $("#aside-contents");}
    const $el = $(element);

    if (!$el.length) {
        console.error('Element not found:', element);
        return;
        
    }

     function fetch(){
         $.get('<?= $path ?>app/frontend/parts/' + part + '.html')
            .done(function (res) {
                $el.html(res);
            })
            .fail(function (xhr, error, msg) {
                console.error('Load failed:', error, msg);
            });
     }
    if ($.trim($el.html()) === '') {
        fetch();
    }else{
        uiStack.push($el.html());
        fetch();
    }
} 
function backToPart(){
    $("#aside-contents").html(uiStack.pop());
}
function killPart(element) {
    const $el = $(element);

    if (!$el.length) {
        console.error('Element not found:', element);
        return;
    }

    if ($.trim($el.html()) !== '') {
        $el.html('');
    }
}

  function getMyId() {
      $.post("@api/api", {
        action:'get-my-id'
      }, function(res) {return res.id}); 
  }
  




</script>
</body>
</html>