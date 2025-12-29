<script>
  lucide.createIcons();
</script>

<!-- drawer init and toggle -->
<div class="text-center">
   <button onclick="$('#drawer-swipe').removeClass('hidden')" class="text-white fixed  bg-gray-700 hover:bg-gray-800 hover:bottom-0 focus:ring-4 focus:ring-gray-300 font-medium rounded-t-full text-sm px-12 -bottom-2 py-1.5  dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800" style="left:50%;transform: translateX(-50%);" type="button" data-drawer-target="drawer-swipe" data-drawer-show="drawer-swipe" data-drawer-placement="bottom" data-drawer-edge="true" data-drawer-edge-offset="bottom-[60px]" aria-controls="drawer-swipe">
   
   </button>
</div>
<!-- drawer component -->
<div id="drawer-swipe" onclick="setTimeout(function(){$('#drawer-swipe').addClass('hidden')},100);" class="fixed hidden z-40 w-full overflow-y-auto bg-white border-t border-gray-200 rounded-t-lg dark:border-gray-700 dark:bg-gray-800 transition-transform bottom-0 left-0 right-0 translate-y-full bottom-[60px]" tabindex="-1" aria-labelledby="drawer-swipe-label">
   <div class="p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700" data-drawer-hide="drawer-swipe">
      <span class="absolute w-8 h-1 -translate-x-1/2 bg-gray-300 rounded-lg top-3 left-1/2 dark:bg-gray-600"></span>
      <h5 id="drawer-swipe-label" class="inline-flex items-center text-base text-gray-700 dark:text-gray-100 dark:text-gray-400 font-medium">
         <i class="uil uil-apps me-2"></i> Quick menu
      </h5>
   </div>

   <div class="grid grid-cols-3 gap-4 p-4 lg:grid-cols-4" onclick="setTimeout(function(){$('#drawer-swipe').addClass('hidden')},100);" data-drawer-toggle="drawer-swipe">

      <!-- Home -->
      <a href='<?= $path ?>@home' target="_blank" class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
         <div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px]">
            <i class="uil uil-home text-gray-700 dark:text-gray-100 text-xl"></i>
         </div>
         <div class="font-medium text-center text-gray-700 dark:text-gray-100 dark:text-gray-400">Home</div>
      </a>

      <!-- Contents -->
      <a href='<?= $path ?>contents' target="_blank" class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
         <div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px]">
            <i class="mdi mdi-file-document-outline text-gray-700 dark:text-gray-100 text-xl"></i>
         </div>
         <div class="font-medium text-center text-gray-700 dark:text-gray-100 dark:text-gray-400">Contents</div>
      </a>

      <!-- Ticket -->
      <a class="hidden p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700 lg:block">
         <div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px]">
            <i class="dripicons-ticket text-gray-700 dark:text-gray-100 text-xl"></i>
         </div>
         <div class="font-medium text-center text-gray-700 dark:text-gray-100 dark:text-gray-400">Ticket</div>
      </a>

      <!-- Analytics -->
      <a href='<?= $path ?>analytics' target="_blank" class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
         <div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px]">
            <i class="fa-solid fa-chart-line text-gray-700 dark:text-gray-100 text-xl"></i>
         </div>
         <div class="font-medium text-center text-gray-700 dark:text-gray-100 dark:text-gray-400">Analytics</div>
      </a>

      <!-- Report -->
      <a href='<?= $path ?>Report' target="_blank" class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
         <div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px]">
            <i class="uil uil-document-info text-gray-700 dark:text-gray-100 text-xl"></i>
         </div>
         <div class="font-medium text-center text-gray-700 dark:text-gray-100 dark:text-gray-400">Report</div>
      </a>

      <!-- Add Content -->
      <a href='<?= $path ?>Create' target="_blank" class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
         <div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px]">
            <i class="fa-solid fa-plus text-gray-700 dark:text-gray-100 text-xl"></i>
         </div>
         <div class="font-medium text-center text-gray-700 dark:text-gray-100 dark:text-gray-400">Add content</div>
      </a>

      <!-- File Manager -->
      <a href='<?= $path ?>Filemanager' target="_blank" class="hidden p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700 lg:block">
         <div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px]">
            <i class="fa fa-folder text-gray-700 dark:text-gray-100 text-xl"></i>
         </div>
         <div class="font-medium text-center text-gray-700 dark:text-gray-100 dark:text-gray-400">File manager</div>
      </a>

      <!-- Tools -->
      <a href='<?= $path ?>Explore' target="_blank" class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
         <div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px]">
            <i class="mdi mdi-tools text-gray-700 dark:text-gray-100 text-xl"></i>
         </div>
         <div class="font-medium text-center text-gray-700 dark:text-gray-100 dark:text-gray-400">Tools</div>
      </a>
   </div>
</div>

<!-- FOOTER -->
 <footer class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 mt-8  hidden">
    <div class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div>
        <h3 class="font-semibold mb-2"><?= $company_name?></h3>
        <ul>
          <li><a href="#" class="hover:text-red-500">About</a></li>
          <li><a href="#" class="hover:text-red-500">Press</a></li>
          <li><a href="#" class="hover:text-red-500">Contact us</a></li>
          <li><a href="#" class="hover:text-red-500">Creators</a></li>
        </ul>
      </div>
      <div>
        <h3 class="font-semibold mb-2">More</h3>
        <ul>
          <li><a href="#" class="hover:text-red-500">Advertise</a></li>
          <li><a href="#" class="hover:text-red-500">Developers</a></li>
          <li><a href="#" class="hover:text-red-500">Terms</a></li>
          <li><a href="#" class="hover:text-red-500">Privacy Policy & Safety</a></li>
        </ul>
      </div>
      <div>
        <h3 class="font-semibold mb-2">How it works</h3>
        <ul>
          <li><a href="#" class="hover:text-red-500">How this website works</a></li>
          <li><a href="#" class="hover:text-red-500">Test new features</a></li>
        </ul>
      </div>
      <div class="col-span-1 sm:col-span-2 lg:col-span-1 flex items-end justify-center lg:justify-end">
        <p class="text-sm">&copy; 2025 Company</p>
      </div>
    </div>
  </footer>



 <!-- Alert Container -->
<div id="alert-container" class="z-50 fixed top-10 left-0 w-full text-center"></div>

<!-- Heroicons CDN -->
<script src="https://unpkg.com/heroicons@2.0.18/dist/heroicons.min.js"></script>

<script>
  const colors = {
    success: 'bg-green-500 text-white',
    error: 'bg-red-500 text-white',
    warning: 'bg-yellow-400 text-black',
    info: 'bg-gray-500 text-white',
    dark: 'bg-gray-800 text-white',
    light: 'bg-gray-200 text-black'
  };

const icons = {
    success: `<span class="inline-flex items-center justify-center h-4 w-4 rounded-full border-1 border border-current mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </span>`,
    error: `<span class="inline-flex items-center justify-center h-4 w-4 rounded-full border-1 border border-current mr-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </span>`,
    warning: `<span class="inline-flex items-center justify-center h-4 w-4 rounded-full border-1 border border-current mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 2a10 10 0 11-10 10A10 10 0 0112 2z" />
                </svg>
              </span>`,
    info: `<span class="inline-flex items-center justify-center h-4 w-4 rounded-full border-1 border border-current mr-2">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
             </svg>
           </span>`,
    light: `<span class="inline-flex items-center justify-center h-4 w-4 rounded-full border-1 border border-current mr-2">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
             </svg>
           </span>`,
    dark: `<span class="inline-flex items-center justify-center h-4 w-4 rounded-full border-1 border border-current mr-2">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
             </svg>
           </span>`
  };

  function showMessage(message, type = 'error', time = 2000) {
    const alertContainer = document.getElementById('alert-container');
    const alert = document.createElement('span');
    alert.className = `shadow-lg transform transition-all duration-300 translate-y-[-20px] rounded-lg px-4 py-2 mb-3 inline-flex items-center justify-center ${colors[type] || colors.info}`;
    alert.innerHTML = `${icons[type] || ''}${message}`;

    alertContainer.appendChild(alert);
    alertContainer.classList.remove('opacity-0');
    // Show animation
    setTimeout(() => alertContainer.classList.add('opacity-100'), 10);

    // Hide after timeout
    setTimeout(() => {
       alertContainer.classList.add('opacity-0');
       alertContainer.innerHTML = '';
    }, time);
  }
</script>



  <!-- SCRIPTS -->
  <script>
    // Dropdown toggles
    const headerSearchInput = document.getElementById('headerSearchInput');
    const searchDropdown = document.getElementById('searchDropdown');
    headerSearchInput.addEventListener('focus', () => {
      searchDropdown.parentElement.classList.add('dropdown-open');
    });
    headerSearchInput.addEventListener('blur', () => {
      setTimeout(() => {
        searchDropdown.parentElement.classList.remove('dropdown-open');
      }, 200);
    });

    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    notifBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      notifDropdown.parentElement.classList.toggle('dropdown-open');
      profileDropdown.parentElement.classList.remove('dropdown-open');
    });

    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    profileBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      profileDropdown.parentElement.classList.toggle('dropdown-open');
      notifDropdown.parentElement.classList.remove('dropdown-open');
    });

    window.addEventListener('click', () => {
      notifDropdown.parentElement.classList.remove('dropdown-open');
      profileDropdown.parentElement.classList.remove('dropdown-open');
    });

    if (localStorage.getItem('theme') === 'dark') {
      document.documentElement.classList.add('dark');
    }

$('#darkModeToggle, .darkModeToggle').on('click', () => {
  document.documentElement.classList.toggle('dark');

  // Save preference in localStorage
  if (document.documentElement.classList.contains('dark')) {
    localStorage.setItem('theme', 'dark');
  } else {
    localStorage.setItem('theme', 'light');
  }
});

  </script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <?php  include 'app/api/ajax.php'; ?>
<script>
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("<?= $path?>service-worker.js")
      .then(() => console.log("Service Worker registered"))
      .catch(err => console.error("SW failed:", err));
  }
</script>

</body>
</html>
