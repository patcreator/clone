<?php include_once "app/auth/isAuth.php";?>
<?php include_once "app/system/cogs/db.php";?>
<?php include_once "app/system/cogs/functions.php";?>
<?php include_once "app/system/api/get_profile.php";?>
<?php include 'app/api/db_helper.php';
$__data = executeQuery("SELECT name,value FROM settings")['data'];
foreach ($__data as $value) {
  $settings_datax[$value['name']] = $value['value'];
}
$logo = $settings_datax['logo']??'app/system/filemanager/logos/logo-color.svg';
$site_name = $settings_datax['name']??'';
$project_description = $settings_datax['description']??'';
$keywords = str_replace(' ',', ', $project_description);
$author = $settings_datax['author']??'';
$domain_name = $settings_datax['url']??'';
$header_menu = $settings_datax['header_menu']??'';
$footer_show = $settings_datax['footer_show']??'';
$about_us_link = $settings_datax['about_us_link']??'';
$terms_link = $settings_datax['terms_link']??'';
$policies_link = $settings_datax['policies_link']??'';
$create_user = $settings_datax['create_user']??'';
$twitter = $settings_datax['twitter']??'';
$company_name = $settings_datax['PATCREATOR']??'';
$user_id = $_SESSION['user_id']??'';
// echo "<pre>";print_r($settings_datax);
// die();
?>
<?php 
   $path = "/clone/whatsapp/";
   $bk = '<button onclick="window.history.back()" title="back" class=" mx-3 bg-gray-200 dark:bg-gray-800 dark:text-gray-200 h-5 w-5 p-5 flex items-center justify-center hover:bg-red-300   top-0 start-0 rounded-xl  dark:border dark:border-gray-600" style="z-index:111 !important;"><i class="fa fa-angle-left"></i></button>';

   $isIconsOpen = $_SESSION['icons']??1;
   if ($isIconsOpen) {
    $logo_h = 'hidden';
    $logo_s = '';
    $f1 = 'md:w-2/5';
     $class0 = ''; $class1 = 'w-64'; $class2 = 'max-w-4xl w-full'; $class3 = 'md:ml-64 ';
   }else{
    $logo_s = 'hidden';
    $logo_h = '';
    $f1 = 'md:w-3/5';
    $class0 = 'hidden'; $class1 = ''; $class2 = ''; $class3 = 'md:ml-32';
   }

// File: app/system/api/load_settings.php

// Path to your settings.json file
$settingsFile = 'app/system/api/settings.json';
$structureFile = 'app/system/api/structure.json';


// Check if file exists
if (!file_exists($settingsFile)) {
    die('Settings file not found.');
}
if (!file_exists($structureFile)) {
    die('Settings file not found.');
}



// Get and decode JSON
$jsonData = file_get_contents($settingsFile);
$config = json_decode($jsonData, true);
$jsonDatax = file_get_contents($structureFile);
$configx = json_decode($jsonDatax, true);

// Check if JSON parsed correctly
if ($config === null) {
    die('Error parsing JSON: ' . json_last_error_msg());
}
// Check if JSON parsed correctly
if ($configx === null) {
    die('Error parsing JSON: ' . json_last_error_msg());
}
$tables = array_keys($configx);

// Example: Access some config values
$config['database'];
$config['project']['name'];
$primary_color = $config['config']['primary_color'];
$accent_color = $config['config']['accent_color'];
 ?>
<style>
  *{
    accent-color: <?= $accent_color ?>;
  }
</style>

<?php 
    $url = $active_user['avatar_url']??'';
    $img = $path."app/system/filemanager/images/avatars/".$url;
    $img = empty($url)?$path.'app/system/filemanager/images/avatars/default.jpeg':$img;



// Fetch all settings and load into $settings associative array
$stmt = $pdo->query("SELECT * FROM settings");
$settings = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['name']] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'value' => $row['value'],
        'type' => $row['type'],
        'status' => $row['status']
    ];
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <!-- <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" /> -->


  <!-- Dropzone JS + CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

  <script>
    tailwind.config = {
      darkMode: 'class',
    };
  </script>



  <style>
    *{
      accent-color: <?= $accent_color ?>;
    }
    .masonry {
      column-count: 4;
      column-gap: 1rem;
    }
    .masonry-item {
      break-inside: avoid;
      margin-bottom: 1rem;
      display: inline-block;
      width: 100%;
    }
    @media (max-width: 1024px) {
      .masonry {
        column-count: 3;
      }
    }
    @media (max-width: 768px) {
      .masonry {
        column-count: 2;
      }
    }
    @media (max-width: 480px) {
      .masonry {
        column-count: 1;
      }
    }
    .sidebar-open {
      transform: translateX(0) !important;
    }
    .dropdown-content {
      display: none;
    }
    .dropdown-open .dropdown-content {
      display: block;
    }
  </style>
  <style>
  @media print {
    body * {
      visibility: hidden;
    }

    #tab-print, #tab-print * {
      visibility: visible;
    }

    #tab-print {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      padding: 0;
      margin: 0;
    }

    .no-print {
      display: none !important;
    }
  }
</style>
<style>

/* Opt-in to customizable styling */
select,
::picker(select) {
  appearance: base-select;
  width: initial;
}

/* Style the select button */
select {
  border: 1px solid #ccc;
  padding: 8px 12px;
  border-radius: 4px;

}

/* Style the dropdown menu (the picker) */
::picker(select) {
  border: 1px solid transparent;
  background: #f9f9f9;
  border-radius: 4px;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
}

/* Style the options within the menu */
option {
  padding: 8px 12px;
  background: white;
}

/* Highlight options on hover or focus */
option:hover,
option:focus {
  background: #e0e0e0;
}
::picker-icon{
    content: '';
    color: rgba(0, 0, 0, 1.0);
}
::checkmark{
    content: '*';
    color: red;
}

/* Scrollbar styling for WebKit browsers (Chrome, Safari, Edge) */
.scrollbar::-webkit-scrollbar {
  width: 8px;
}

.scrollbar::-webkit-scrollbar-track {
  background: transparent; /* Tailwind gray-100 */
}

.scrollbar::-webkit-scrollbar-thumb {
  background-color: #6b7280; /* Tailwind gray-500 */
  border-radius: 9999px;
  /*border: 2px solid #f3f4f6;*/
}

.scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #374151; /* Tailwind gray-700 */
}

/* Firefox scrollbar */
html {
  /*scrollbar-width: thin;*/
  scrollbar-color: #6b7280 transparent;
}


</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

<!-- Unicons -->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

<!-- Dripicons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dripicons/webfont/webfont.css">

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="<?= $project_description ?>"/>
  <meta name="keywords" content="<?= $keywords ?>"/>
  <meta name="author" content="<?= $author ?>" />

  <!-- Open Graph / Facebook / WhatsApp -->
  <meta property="og:title" content="<?= $site_name ?> "/>
  <meta property="og:description" content="<?= $project_description ?>"/>
  <meta property="og:image" content="<?= $domain_name ?>app/settings/preview.jpg"/>
  <meta property="og:url" content="<?= $domain_name ?>"/>
  <meta property="og:type" content="website"/>

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image"/>
  <meta name="twitter:title" content="<?= $site_name ?>"/>
  <meta name="twitter:description" content="<?= $project_description ?>"/>
  <meta name="twitter:image" content="<?= $domain_name ?>app/settings/preview.jpg"/>
  <meta name="twitter:site" content="@<?= $twitter?>"/>

  <!-- App / Mobile Support -->
  <meta name="theme-color" content="#ffffff"/>
  <meta name="mobile-web-app-capable" content="yes"/>
  <meta name="apple-mobile-web-app-capable" content="yes"/>
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
  <meta name="apple-mobile-web-app-title" content="<?= $project_name ?>"/>
  <link rel="manifest" href="<?= $path?>manifest.json"/>

  <!-- Favicons -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $path?>app/system/filemanager/favicons/favicon-32x32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $path?>app/system/filemanager/favicons/favicon-16x16.png"/>
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $path?>app/system/filemanager/favicons/apple-touch-icon.png"/>
  <link rel="mask-icon" href="<?= $path?>app/system/filemanager/favicons/safari-pinned-tab.svg" color="#e60023"/>
  <meta name="msapplication-TileColor" content="#ffffff"/>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">

  <!-- HEADER -->
  <header id="header" class="fixed  border-b dark:border-gray-700 top-0 left-0 w-full z-30 bg-white dark:bg-gray-900 px-4 py-2 flex items-center justify-between" style="z-index: 1;">

    
    
<script>
  function toggleIcons() {
    $('[data-txt]').toggleClass('hidden');
    $('#sidebar').toggleClass('w-64');
    $('#dashboardSection').toggleClass('max-w-4xl w-full');
    $('#main-content').toggleClass('md:ml-64 md:ml-32');
    $('#f1,.f1').toggleClass('md:w-3/5 md:w-2/5');
    $('.logo-s').toggleClass('hidden');
    $('.logo-h').toggleClass('hidden');
    $.post('app/api/toggleSessions.php',{icons:true});
  }
</script>


<style>
  .hidden-1{
    display: none !important;
    opacity: 0 !important;
  }
</style>
<div id="offline" class="hidden-1" style="height: 100vh;width: 100vw;background-color: #f6fbff; position: fixed;flex-direction: column;top: 0;left: 0;z-index: 1111;display: flex;justify-content: center;align-items: center;user-select: none;">
  <h1 style="font-size: 10rem;filter: brightness(0.4)  invert(1) saturate(0);">🛜</h1>
  <strong style="color:#666;">You are Offline</strong>
</div>
<script>
  if (!navigator.onLine) {
    document.querySelector('#offline').classList.remove('hidden');
  } else {
    document.querySelector('#offline').classList.add('hidden');
  }

  // Optional: Listen for changes in connection
  window.addEventListener('online', () => {
    document.querySelector('#offline').classList.add('hidden');
  });

  window.addEventListener('offline', () => {
    document.querySelector('#offline').classList.remove('hidden');
  });
</script>



<div class=" flex items-center mx-auto <?= $f1 ?>" id="f1">   
     <button class="text-gray-700  md:hidden inline hover:bg-gray-200 dark:hover:bg-gray-800 px-3 rounded-full dark:text-white font-medium me-2 py-3" type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation" aria-controls="drawer-navigation">
   <i class="uil uil-bars"></i>
   </button>
   <?= $bk??'' ?>
  <a href = '<?= $path?>' class=" logo-h <?= $logo_h ?>">
        <!-- <?= $logo ?> -->
        <img src="<?= $path?><?= $logo?>" class="h-12">
    </a>
    <label for="voice-search" class="sr-only">Search</label>
    <div class="relative w-full">
        <input type="text" id="headerSearchInput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full ps-5 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white pe-9 dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Search ..." required />
        <button  id="voiceSearchBtn" type="button" class="absolute inset-y-0 end-0 flex items-center pe-3">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7v3a5.006 5.006 0 0 1-5 5H6a5.006 5.006 0 0 1-5-5V7m7 9v3m-3 0h6M7 1h2a3 3 0 0 1 3 3v5a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V4a3 3 0 0 1 3-3Z"/>
            </svg>
        </button>
    </div>
    <button type="button" id="headerSearchBtn" class="inline-flex items-center py-2.5 px-3 ms-2 text-sm font-medium text-white bg-red-700 rounded-lg border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
        <svg class="w-4 h-4 md:me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
        </svg> <span class="hidden md:inline-flex">Search</span>
    </button>
    <!-- Dropdown -->
  <div
    id="searchDropdown"
    class="hidden absolute top-full mt-1 w-full dark:bg-gray-800 z-50"
  >
    <ul id="searchResults" class="<?= $f1 ?> f1"></ul>
  </div>
</div>





<script>
  const input = document.getElementById('headerSearchInput');
  const dropdown = document.getElementById('searchDropdown');
  const resultsList = document.getElementById('searchResults');
  const voiceBtn = document.getElementById('voiceSearchBtn');

  // --- Live Search (AJAX to backend) ---
  input.addEventListener('input', async () => {
    const query = input.value.trim();
    if (query.length < 2) {
      dropdown.classList.add('hidden');
      return;
    }

    // Fetch top 5 results from backend
    const res = await fetch(`<?= $path?>search_suggestions?q=${encodeURIComponent(query)}`);
    const results = await res.json();

    resultsList.innerHTML = results
      .map(
        (r) => `
        <li>
          <a href="${r.url}" class="block px-4 py-2 hover:bg-gray-300 rounded mb-1 dark:hover:bg-gray-700 cursor-pointer">
            <span class="font-semibold">${r.title}</span>
            <span class="block text-sm text-gray-500">${r.keywords}</span>
          </a>
        </li>`
      )
      .join('');

    dropdown.classList.remove('hidden');
  });

  // Hide dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) dropdown.classList.add('hidden');
  });

  // --- Voice Search ---
  if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    recognition.lang = 'en-US';
    recognition.continuous = false;

    voiceBtn.addEventListener('click', () => {
      recognition.start();
      voiceBtn.classList.add('text-red-500');
    });

    recognition.onresult = (event) => {
      const transcript = event.results[0][0].transcript;
      input.value = transcript;
      voiceBtn.classList.remove('text-red-500');
      input.dispatchEvent(new Event('input')); // Trigger search
    };

    recognition.onerror = () => {
      voiceBtn.classList.remove('text-red-500');
      showMessage('Raise your tone...');
    };
  } else {
    voiceBtn.style.display = 'none';
  }
</script>




















    <!-- Right: icons -->
    <div class="flex items-center md:space-x-3">

      <!-- Create icon -->
      <a href="<?= $path ?>s/Create" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
      </a>








      <!-- Notification -->
      <div class="relative">
        <button id="notifBtn" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
          <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>
        <div id="notifDropdown" class="dropdown-content absolute right-0 top-full mt-1 w-60 bg-white dark:bg-gray-800 shadow-3xl border dark:border-gray-700 rounded-lg">
          <div>
            <?php  
                $notifications = executeQuery("SELECT * FROM `notifications` WHERE user_id = $user_id")['data'];
                if ($notifications) {
                  format_output($notifications,'@for<a href="*link" class="px-4 block py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer rounded-t-lg rounded-b-none">*title</a>@end');
                }else{
                  ?>
                  <div class="flex flex-col text-gray-500 text-center p-5">
                    <i class="mdi mdi-bell text-5xl"></i>
                  <span class="px-4 block py-2">0 Notification found</span>
                  </div>
                  <?php
                }
             ?>
          </div>
        </div>
      </div>

      <!-- Profile -->
      <div class="relative">
        <button id="profileBtn" class="p-1 rounded-full hover:ring-2 w-8 h-8 ring-red-500">
          <img src="<?= $img ?>" alt="User Avatar" class=" rounded-full h-full w-full">
        </button>
        <div id="profileDropdown" class="dropdown-content absolute right-0 top-full mt-1 w-48 bg-white dark:bg-gray-800 shadow-lg dark:border-gray-700 border rounded-lg">
          <div>
            <a href="<?= $path?>profile" class="px-4 py-2 block hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer rounded-t-lg rounded-b-none">
            <i class="mdi mdi-account me-3"></i> Profile</a>
            <a href="<?= $path?>settings" class="px-4 py-2 block hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
            <i class="mdi mdi-cogs me-3"></i> Settings</a>
            <a href="<?= $path?>explore" class="px-4 py-2 block hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
            <i class="mdi mdi-robot me-3"></i> Explore</a>
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
            <a href="<?= $path?>report" class="px-4 py-2 block hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
            <i class="mdi mdi-history me-3"></i> Report history</a>
            <a href="<?= $path?>help" class="px-4 py-2 block hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer
              "><i class="mdi mdi-help me-3"></i> Help</a>
            <a href="<?= $path?>feedback" class="px-4 py-2 block hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
            <i class="mdi mdi-message me-3"></i> Send feedback</a>
            <a href="<?= $path?>logout/" class="px-4 py-2 block hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer rounded-t-none rounded-b-lg">
            <i class="mdi mdi-logout me-3"></i> Logout</a>
          </div>
        </div>
      </div>
    </div>
  </header>



<!-- Lucide CDN -->
<script src="https://unpkg.com/lucide@latest"></script>

<nav id="sidebar" class="hidden lg:block fixed left-0 border-e dark:border-gray-700 top-0 max-h-screen mt-0 z-50 <?= $class1 ?>  bg-white dark:bg-gray-900  z-20 transform-translate-x-full md:translate-x-0 transition-transform duration-300 ">
        <div class="flex p-4">

      <button class="hover:bg-gray-100 p-2 rounded-full px-3 hover:text-red-500" onclick="toggleIcons()"><i class="dripicons-menu"></i></button>
    <!-- Logo -->
        <a href = '<?= $path?>' class="flex items-center space-x-2 logo-s <?= $logo_s ?>">
        <!-- <?= $logo ?> -->
        <img src="<?= $path?><?= $logo?>">
      <span class="text-xl font-bold text-gray-800 dark:text-white "><?= $site_name ?></span>
    </a>
    </div>
  <div  class="overflow-y-hidden scrollbar hover:overflow-y-auto h-[90vh] p-4">
    <ul class="space-y-2">
        <!-- Extra Section -->
      <li class="mt-4"><span class="text-gray-500 dark:text-gray-400 uppercase text-xs">Extra</span></li>

      <li>
        <a title="Dashboard" href="<?= $path?>Dashboard" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Dashboard" data-lucide="home" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Dashboard</span>

        </a>
      </li>

      <li>
        <a title="Search" href="<?= $path?>Search" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Search" data-lucide="search" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Search</span>
        </a>
      </li>

      <li>
        <a title="Create" href="<?= $path?>Create" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Create" data-lucide="plus-square" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Create</span>
        </a>
      </li>

      <li>
        <a title="Content" href="<?= $path?>Content" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Content" data-lucide="file-text" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Content</span>
        </a>
      </li>

      <li>
        <a title="Analytics" href="<?= $path?>Analytics" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Analytics" data-lucide="bar-chart-2" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Analytics</span>
        </a>
      </li>
      <!-- You Section -->
      <li><span class="text-gray-500 dark:text-gray-400 uppercase text-xs">You</span></li>

      <li>
        <a title="History" href="<?= $path?>History" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="History" data-lucide="list" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">History</span>
        </a>
      </li>

      <!-- Your Section -->
      <li class="mt-4"><span class="text-gray-500 dark:text-gray-400 uppercase text-xs">Your</span></li>

      <li>
        <a title="Profile" href="<?= $path?>Profile" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Profile" data-lucide="user" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Profile</span>
        </a>
      </li>

      <li>
        <a title="Settings" href="<?= $path?>Settings" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Settings" data-lucide="settings" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Settings</span>
        </a>
      </li>

      <li>
        <a title="Explore" href="<?= $path?>Explore" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Explore" data-lucide="compass" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Explore</span>
        </a>
      </li>

      <!-- Settings Section -->
      <li class="mt-4"><span class="text-gray-500 dark:text-gray-400 uppercase text-xs">Settings</span></li>

      <li>
        <a title="Report" href="<?= $path?>Report" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Report" data-lucide="flag" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Report</span>
        </a>
      </li>

      <li>
        <a title="Help" href="<?= $path?>Help" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Help" data-lucide="help-circle" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Help</span>
        </a>
      </li>

      <li>
        <a title="Send" href="<?= $path?>feedback" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Send feedback" data-lucide="message-square" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>">Send feedback</span>
        </a>
      </li>

      <li>
        <a title="Sql runner" href="<?= $path?>query" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Sql runner" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300 fa fa-code"></i>

          <span data-txt class="<?= $class0 ?>">SQL runner</span>
        </a>
      </li>

      <li>
        <a title="File Manager" href="<?= $path?>filemanager" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="File Manager" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300 fa fa-folder"></i>

          <span data-txt class="<?= $class0 ?>">File Manager</span>
        </a>
      </li>
      <li>
        <a title="cmd" href="<?= $path?>cmd" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="cmd" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300 fa fa-terminal"></i>

          <span data-txt class="<?= $class0 ?>">CMD</span>
        </a>
      </li>

      <li>
        <button title="Dark Mode" id="darkModeToggle" class="flex items-center w-full px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Dark Mode" data-lucide="moon" id="darkModeIcon" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span data-txt class="<?= $class0 ?>"><span id="darkModeText">Dark Mode</span></span>
        </button>
      </li>
    </ul>
  </div>
</nav>


<!-- drawer component -->
<div id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-navigation-label">
    <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400">Menu</h5>
    <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        <span class="sr-only">Close menu</span>
    </button>
  <div class="py-4 overflow-y-auto">

        <ul class="space-y-2 font-medium">

  <!-- Extra Section -->
  <li class="mt-4">
    <span class="text-gray-500 dark:text-gray-400 uppercase text-xs px-2">Extra</span>
  </li>

  <li>
    <a href="<?= $path ?>Dashboard" title="Dashboard" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="home" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Dashboard</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>Search" title="Search" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="search" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Search</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>Create" title="Create" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="plus-square" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Create</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>Content" title="Content" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="file-text" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Content</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>Analytics" title="Analytics" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="bar-chart-2" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Analytics</span>
    </a>
  </li>

  <!-- You Section -->
  <li class="mt-4">
    <span class="text-gray-500 dark:text-gray-400 uppercase text-xs px-2">You</span>
  </li>

  <li>
    <a href="<?= $path ?>History" title="History" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="list" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">History</span>
    </a>
  </li>

  <!-- Your Section -->
  <li class="mt-4">
    <span class="text-gray-500 dark:text-gray-400 uppercase text-xs px-2">Your</span>
  </li>

  <li>
    <a href="<?= $path ?>Profile" title="Profile" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="user" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Profile</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>Settings" title="Settings" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="settings" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Settings</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>Explore" title="Explore" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="compass" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Explore</span>
    </a>
  </li>

  <!-- Settings Section -->
  <li class="mt-4">
    <span class="text-gray-500 dark:text-gray-400 uppercase text-xs px-2">Settings</span>
  </li>

  <li>
    <a href="<?= $path ?>Report" title="Report" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="flag" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Report</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>Help" title="Help" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="help-circle" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Help</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>feedback" title="Send Feedback" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="message-square" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">Send Feedback</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>query" title="SQL Runner" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i class="fa fa-code w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">SQL Runner</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>filemanager" title="File Manager" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i class="fa fa-folder w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">File Manager</span>
    </a>
  </li>

  <li>
    <a href="<?= $path ?>cmd" title="CMD" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i class="fa fa-terminal w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3">CMD</span>
    </a>
  </li>

  <li>
    <button id="darkModeToggle" title="Dark Mode" class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i data-lucide="moon" id="darkModeIcon" class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
      <span class="ms-3"><span id="darkModeText">Dark Mode</span></span>
    </button>
  </li>

</ul>
      

   </div>
</div>
