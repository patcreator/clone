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
